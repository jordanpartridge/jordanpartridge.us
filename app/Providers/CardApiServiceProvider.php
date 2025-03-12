<?php

namespace App\Providers;

use App\Contracts\CardServiceInterface;
use App\Http\Integrations\CardApi\CardApi;
use App\Services\CardService;
use Illuminate\Support\ServiceProvider;

class CardApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CardApi::class, function ($app) {
            $apiKey = config('services.card_api.api_key');
            $baseUrl = config('services.card_api.base_url', 'https://card-api.jordanpartridge.com/api/v1');

            return new CardApi($apiKey, $baseUrl);
        });

        $this->app->singleton(CardService::class, function ($app) {
            return new CardService($app->make(CardApi::class));
        });

        $this->app->bind(CardServiceInterface::class, CardService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
