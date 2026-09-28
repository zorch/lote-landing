<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('home');
Route::view('/privacidad', 'privacidad')->name('privacy');
Route::view('/terminos', 'terminos')->name('terms');

Route::get('/sitemap.xml', fn () => response()
    ->view('sitemap', ['lastmod' => now()->toDateString()])
    ->header('Content-Type', 'application/xml'))->name('sitemap');
