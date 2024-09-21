<?php

namespace App\Providers;

use App\Http\Integrations\CardApi\CardApi;
use Illuminate\Support\ServiceProvider;

class CardApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CardApi::class, function ($app) {
            return new CardApi(config('services.card_api.api_key'), config('services.card_api.base_url'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
