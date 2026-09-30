<?php

namespace App\Providers;

use App\Services\AppStoreReceipt;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AppStoreReceipt::class, fn () => AppStoreReceipt::withAppleRoot());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The contact address appears on every page.
        View::composer('*', fn ($view) => $view->with([
            'email' => config('landing.email'),
            'whatsapp' => 'https://wa.me/'.config('landing.whatsapp').'?text='.rawurlencode(config('landing.whatsapp_message')),
        ]));
    }
}
