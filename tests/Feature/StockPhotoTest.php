<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StockPhotoTest extends TestCase
{
    public function test_photos_come_simplified_and_cached(): void
    {
        Cache::flush();
        config(['services.pixabay.key' => 'px-test']);
        Http::fake(['pixabay.com/api/*' => Http::response(['hits' => [[
            'id' => 7, 'webformatURL' => 'https://cdn/7.jpg', 'webformatWidth' => 640, 'webformatHeight' => 960,
            'pageURL' => 'https://pixabay.com/photos/7', 'tags' => 'money, coins',
        ]]])]);
        $this->getJson('/api/stock-photos?q=money&lang=en')->assertOk()
            ->assertJsonPath('photos.0.id', 7)->assertJsonPath('photos.0.height', 960)->assertDontSee('px-test');
        $this->getJson('/api/stock-photos?q=money&lang=en')->assertOk();
        Http::assertSentCount(1);
    }

    public function test_without_key_it_says_so(): void
    {
        config(['services.pixabay.key' => null]);
        $this->getJson('/api/stock-photos?q=money')->assertStatus(503);
    }
}
