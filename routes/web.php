<?php

use App\Http\Controllers\StockVideoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('home');
Route::view('/privacidad', 'privacidad')->name('privacy');
Route::view('/terminos', 'terminos')->name('terms');
Route::view('/soporte', 'soporte')->name('support');

// Lote app: stock clips (Pixabay), cached. Limited per phone so nobody drains the key.
Route::get('/api/stock-videos', [StockVideoController::class, 'search'])
    ->middleware('throttle:30,1')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('stock-videos');

Route::get('/sitemap.xml', fn () => response()
    ->view('sitemap', ['lastmod' => now()->toDateString()])
    ->header('Content-Type', 'application/xml'))->name('sitemap');
