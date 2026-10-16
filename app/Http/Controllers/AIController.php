<?php

namespace App\Http\Controllers;

use App\Services\AppStoreReceipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * "IA de Lote": the app's AI requests through Lote's own key (Claude when
 * ANTHROPIC_KEY is set, OpenAI otherwise), for Lote Pro subscribers. The
 * signed App Store transaction proves the subscription; the key never
 * leaves the server.
 */
class AIController extends Controller
{
    /** Subscriptions that include Lote's AI. */
    public const PRODUCTS = ['com.applote.lote.pro.monthly', 'com.applote.lote.pro.yearly'];

    public const BUNDLES = ['com.applote.lote'];

    /** Days a lapsed subscription keeps working, so a late renewal doesn't cut it off. */
    private const GRACE_DAYS = 3;

    public function __construct(private readonly AppStoreReceipt $receipts) {}

    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'system' => ['required', 'string', 'max:20000'],
            'user' => ['required', 'string', 'max:120000'],
            'json' => ['nullable', 'boolean'],
            // A JSON Schema the answer must follow (animations, clips…).
            'schema' => ['nullable', 'array'],
        ]);
        $anthropic = config('services.anthropic.key');
        $openai = config('services.openai.key');
        if (! $anthropic && ! $openai) {
            return response()->json(['message' => 'La IA de Lote todavía no está configurada.'], 503);
        }

        $denied = $this->entitlementProblem($request);
        if ($denied) {
            return response()->json(['message' => $denied], 402);
        }

        $content = $anthropic
            ? $this->askClaude($anthropic, $validated)
            : $this->askOpenAI($openai, $validated);
        if ($content === null) {
            return response()->json(['message' => 'La IA no respondió. Intenta más tarde.'], 502);
        }
        if ($content === '') {
            return response()->json(['message' => 'La IA regresó una respuesta vacía.'], 502);
        }

        return response()->json(['content' => $content]);
    }

    /** The answer text, '' when empty, null when the request failed. */
    private function askClaude(string $key, array $validated): ?string
    {
        $system = $validated['system'];
        $schema = $validated['schema'] ?? null;
        if (($validated['json'] ?? false) && ! $schema) {
            $system .= "\n\nResponde únicamente con un objeto JSON válido, sin texto antes ni después.";
        }
        $output = ['effort' => config('services.anthropic.effort')];
        if ($schema) {
            $output['format'] = ['type' => 'json_schema', 'schema' => $schema];
        }
        $response = Http::withHeaders([
            'x-api-key' => $key,
            'anthropic-version' => '2023-06-01',
            'anthropic-beta' => 'server-side-fallback-2026-07-01',
        ])->timeout(150)->post('https://api.anthropic.com/v1/messages', [
            'model' => config('services.anthropic.model'),
            'max_tokens' => 16000,
            'system' => $system,
            'messages' => [['role' => 'user', 'content' => $validated['user']]],
            'output_config' => $output,
            // Refusals are retried on another Claude by the API itself.
            'fallbacks' => 'default',
        ]);
        if (! $response->successful()) {
            return null;
        }
        if ($response->json('stop_reason') === 'refusal') {
            return '';
        }
        foreach ($response->json('content', []) as $block) {
            if (($block['type'] ?? '') === 'text' && is_string($block['text'] ?? null) && $block['text'] !== '') {
                return $block['text'];
            }
        }

        return '';
    }

    private function askOpenAI(string $key, array $validated): ?string
    {
        $body = [
            'model' => config('services.openai.model'),
            'messages' => [
                ['role' => 'system', 'content' => $validated['system']],
                ['role' => 'user', 'content' => $validated['user']],
            ],
        ];
        if (! empty($validated['schema'])) {
            $body['response_format'] = ['type' => 'json_schema', 'json_schema' => ['name' => 'answer', 'schema' => $validated['schema'], 'strict' => true]];
        } elseif ($validated['json'] ?? false) {
            $body['response_format'] = ['type' => 'json_object'];
        }
        $response = Http::withToken($key)->timeout(150)->post('https://api.openai.com/v1/chat/completions', $body);
        if (! $response->successful()) {
            return null;
        }
        $content = $response->json('choices.0.message.content');

        return is_string($content) ? $content : '';
    }

    /** Why this request may not use Lote's AI, or null when it may. */
    private function entitlementProblem(Request $request): ?string
    {
        $dev = config('services.lote.dev_token');
        if ($dev && hash_equals($dev, (string) $request->header('X-Lote-Dev', ''))) {
            return null;
        }
        $jws = (string) $request->header('X-Lote-Receipt', '');
        if ($jws === '') {
            return 'La IA de Lote es parte de Lote Pro.';
        }
        // Verified once per receipt: the chain and signature checks aren't free.
        $claims = Cache::remember('receipt:'.hash('sha256', $jws), now()->addHour(), fn () => $this->receipts->verify($jws) ?? false);
        if (! $claims) {
            return 'No se pudo comprobar tu suscripción. Abre Lote Pro y toca "Restaurar compras".';
        }
        if (! in_array($claims['bundleId'], self::BUNDLES, true) || ! in_array($claims['productId'], self::PRODUCTS, true)) {
            return 'Esa compra no incluye la IA de Lote.';
        }
        if ($claims['revocationDate'] !== null) {
            return 'Tu suscripción fue reembolsada o cancelada.';
        }
        $expires = $claims['expiresDate'];
        if ($expires !== null && $expires / 1000 + self::GRACE_DAYS * 86400 < time()) {
            return 'Tu suscripción a Lote Pro terminó. Renuévala para seguir usando la IA de Lote.';
        }

        return null;
    }
}
