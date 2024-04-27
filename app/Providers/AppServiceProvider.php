<?php

namespace App\Providers;

use App\Models\Ride;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Ride::observe(\App\Observers\RideObserver::class);
    }
}
