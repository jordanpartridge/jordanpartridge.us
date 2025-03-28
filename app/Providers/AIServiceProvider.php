<?php

namespace App\Providers;

use App\Services\AI\AIContentService;
use App\Services\AI\Handlers\ExceptionHandler;
use App\Services\AI\Handlers\FallbackHandler;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the exception handler
        $this->app->singleton(ExceptionHandler::class, function ($app) {
            return new ExceptionHandler();
        });

        // Register the fallback handler
        $this->app->singleton(FallbackHandler::class, function ($app) {
            return new FallbackHandler(
                $app->make('prism'),
                $app->make(ExceptionHandler::class)
            );
        });

        // Register the AI content service
        $this->app->singleton(AIContentService::class, function ($app) {
            return new AIContentService(
                $app->make('prism'),
                $app->make(ExceptionHandler::class),
                $app->make(FallbackHandler::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
