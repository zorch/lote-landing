<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Stock photos for Lote's animations ("photo" moments). Same idea as the
 * clips: the app asks here so the Pixabay key never ships, and every
 * search is cached for 24 hours as Pixabay's terms require.
 */
class StockPhotoController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
            'lang' => ['nullable', 'in:es,en'],
        ]);
        $key = config('services.pixabay.key');
        if (! $key) {
            return response()->json(['message' => 'Las fotos todavía no están configuradas.'], 503);
        }

        $query = mb_strtolower(trim($validated['q']));
        $lang = $validated['lang'] ?? 'en';
        $cacheKey = 'pixabay:photos:'.$lang.':'.md5($query);

        $photos = Cache::remember($cacheKey, now()->addDay(), function () use ($key, $query, $lang) {
            $response = Http::timeout(10)->get('https://pixabay.com/api/', [
                'key' => $key,
                'q' => $query,
                'lang' => $lang,
                'image_type' => 'photo',
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

        if ($photos === null) {
            Cache::forget($cacheKey);

            return response()->json(['message' => 'Pixabay no respondió. Intenta más tarde.'], 502);
        }

        return response()->json(['source' => 'Pixabay', 'photos' => $photos]);
    }

    /** A phone-sized file (640 px wide) and its size. */
    private static function simplify(array $hit): ?array
    {
        $url = $hit['webformatURL'] ?? null;
        if (! $url) {
            return null;
        }

        return [
            'id' => $hit['id'],
            'url' => $url,
            'width' => $hit['webformatWidth'] ?? null,
            'height' => $hit['webformatHeight'] ?? null,
            'page' => $hit['pageURL'] ?? null,
            'tags' => $hit['tags'] ?? '',
        ];
    }
}
