<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StockVideoTest extends TestCase
{
    private function fakePixabay(): void
    {
        config(['services.pixabay.key' => 'test-key']);
        Http::fake(['pixabay.com/*' => Http::response(['hits' => [[
            'id' => 1, 'duration' => 12, 'pageURL' => 'https://pixabay.com/videos/1', 'tags' => 'money, cash',
            'videos' => ['small' => ['url' => 'https://cdn.pixabay.com/v/1_small.mp4', 'width' => 1280, 'height' => 720, 'thumbnail' => 't.jpg']],
        ]]])]);
    }

    public function test_returns_simplified_videos(): void
    {
        $this->fakePixabay();
        $this->getJson('/api/stock-videos?q=dinero')
            ->assertOk()
            ->assertJsonPath('source', 'Pixabay')
            ->assertJsonPath('videos.0.url', 'https://cdn.pixabay.com/v/1_small.mp4')
            ->assertJsonPath('videos.0.duration', 12);
    }

    public function test_caches_each_search_for_a_day(): void
    {
        Cache::flush();
        $this->fakePixabay();
        $this->getJson('/api/stock-videos?q=Dinero');
        $this->getJson('/api/stock-videos?q=dinero');
        Http::assertSentCount(1);
    }

    public function test_the_key_never_reaches_the_app(): void
    {
        $this->fakePixabay();
        $this->getJson('/api/stock-videos?q=dinero')->assertDontSee('test-key');
    }

    public function test_without_a_key_it_says_so(): void
    {
        config(['services.pixabay.key' => null]);
        $this->getJson('/api/stock-videos?q=dinero')->assertStatus(503);
    }

    public function test_needs_a_search_term(): void
    {
        $this->getJson('/api/stock-videos')->assertStatus(422);
    }
}
