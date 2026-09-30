<?php

namespace Tests\Feature;

use App\Services\AppStoreReceipt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AITest extends TestCase
{
    private function fakeOpenAI(): void
    {
        config(['services.openai.key' => 'sk-test']);
        Http::fake(['api.openai.com/*' => Http::response(['choices' => [['message' => ['content' => '{"titulo":"Hola"}']]]])]);
    }

    /** A verifier that accepts "good" and rejects everything else. */
    private function fakeReceipts(array $claims = []): void
    {
        Cache::flush();
        $this->app->instance(AppStoreReceipt::class, new class($claims) extends AppStoreReceipt
        {
            public function __construct(private array $claims)
            {
                parent::__construct('');
            }

            public function verify(string $jws): ?array
            {
                return $jws === 'good' ? $this->claims + [
                    'bundleId' => 'com.applote.lote', 'productId' => 'com.applote.lote.pro.monthly', 'environment' => 'Sandbox',
                    'expiresDate' => (time() + 3600) * 1000, 'revocationDate' => null, 'originalTransactionId' => '1',
                ] : null;
            }
        });
    }

    private function ask(array $headers = []): \Illuminate\Testing\TestResponse
    {
        return $this->withHeaders($headers)->postJson('/api/ai/chat', ['system' => 'Eres útil', 'user' => 'Hola', 'json' => true]);
    }

    public function test_pro_receipt_gets_an_answer_and_the_key_stays_home(): void
    {
        $this->fakeOpenAI();
        $this->fakeReceipts();
        $this->ask(['X-Lote-Receipt' => 'good'])->assertOk()->assertJsonPath('content', '{"titulo":"Hola"}')->assertDontSee('sk-test');
        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer sk-test')
            && $request['response_format']['type'] === 'json_object');
    }

    public function test_without_receipt_it_asks_for_pro(): void
    {
        $this->fakeOpenAI();
        $this->fakeReceipts();
        $this->ask()->assertStatus(402)->assertJsonPath('message', 'La IA de Lote es parte de Lote Pro.');
        Http::assertNothingSent();
    }

    public function test_bad_receipt_is_refused(): void
    {
        $this->fakeOpenAI();
        $this->fakeReceipts();
        $this->ask(['X-Lote-Receipt' => 'forged'])->assertStatus(402);
    }

    public function test_expired_and_refunded_subscriptions_are_refused(): void
    {
        $this->fakeOpenAI();
        $this->fakeReceipts(['expiresDate' => (time() - 10 * 86400) * 1000]);
        $this->ask(['X-Lote-Receipt' => 'good'])->assertStatus(402);
        $this->fakeReceipts(['revocationDate' => time() * 1000]);
        $this->ask(['X-Lote-Receipt' => 'good'])->assertStatus(402);
    }

    public function test_recently_lapsed_subscription_still_works(): void
    {
        $this->fakeOpenAI();
        $this->fakeReceipts(['expiresDate' => (time() - 86400) * 1000]);
        $this->ask(['X-Lote-Receipt' => 'good'])->assertOk();
    }

    public function test_other_products_do_not_count(): void
    {
        $this->fakeOpenAI();
        $this->fakeReceipts(['productId' => 'com.other.app.pro']);
        $this->ask(['X-Lote-Receipt' => 'good'])->assertStatus(402);
    }

    public function test_dev_token_works_without_receipt(): void
    {
        $this->fakeOpenAI();
        $this->fakeReceipts();
        config(['services.lote.dev_token' => 'dev-secret']);
        $this->ask(['X-Lote-Dev' => 'dev-secret'])->assertOk();
        $this->ask(['X-Lote-Dev' => 'wrong'])->assertStatus(402);
    }

    public function test_without_key_it_says_so(): void
    {
        config(['services.openai.key' => null]);
        $this->ask(['X-Lote-Receipt' => 'good'])->assertStatus(503);
    }
}
