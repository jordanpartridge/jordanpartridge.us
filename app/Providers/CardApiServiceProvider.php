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
            return new CardApi(config('services.card_api.api_key'), config('services.card_api.base_url'));
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
