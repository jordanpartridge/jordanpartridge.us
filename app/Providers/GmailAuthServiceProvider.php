<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GmailAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register middleware for web routes
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', \App\Http\Middleware\CheckPendingGmailAuth::class);
    }
}
