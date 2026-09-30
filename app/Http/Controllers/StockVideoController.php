<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Stock clips for Lote's "videos de apoyo". The app asks here instead of
 * Pixabay so the API key never ships inside the app, and every search is
 * cached for 24 hours as Pixabay's terms require.
 */
class StockVideoController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
            'lang' => ['nullable', 'in:es,en'],
        ]);
        $key = config('services.pixabay.key');
        if (! $key) {
            return response()->json(['message' => 'Los videos de apoyo todavía no están configurados.'], 503);
        }

        $query = mb_strtolower(trim($validated['q']));
        $lang = $validated['lang'] ?? 'es';
        $cacheKey = 'pixabay:videos:'.$lang.':'.md5($query);

        $videos = Cache::remember($cacheKey, now()->addDay(), function () use ($key, $query, $lang) {
            $response = Http::timeout(10)->get('https://pixabay.com/api/videos/', [
                'key' => $key,
                'q' => $query,
                'lang' => $lang,
                'video_type' => 'film',
                'safesearch' => 'true',
                'per_page' => 20,
            ]);
            if (! $response->successful()) {
                return null;
            }

            return collect($response->json('hits', []))
                ->map(fn (array $hit) => self::simplify($hit))
                ->filter()
                ->values()
                ->all();
        });

        if ($videos === null) {
            Cache::forget($cacheKey);

            return response()->json(['message' => 'Pixabay no respondió. Intenta más tarde.'], 502);
        }

        return response()->json(['source' => 'Pixabay', 'videos' => $videos]);
    }

    /** Only what the app needs: a phone-sized file, its size and length. */
    private static function simplify(array $hit): ?array
    {
        $files = $hit['videos'] ?? [];
        $file = collect(['small', 'medium', 'tiny'])
            ->map(fn ($size) => $files[$size] ?? null)
            ->first(fn ($candidate) => ! empty($candidate['url']));
        if (! $file) {
            return null;
        }

        return [
            'id' => $hit['id'],
            'url' => $file['url'],
            'width' => $file['width'] ?? null,
            'height' => $file['height'] ?? null,
            'duration' => $hit['duration'] ?? null,
            'thumbnail' => $file['thumbnail'] ?? null,
            'page' => $hit['pageURL'] ?? null,
            'tags' => $hit['tags'] ?? '',
        ];
    }
}
