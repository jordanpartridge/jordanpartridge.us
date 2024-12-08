<?php

namespace App\Providers;

use App\Models\Ride;
use App\Models\User;
use App\Observers\RideObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this
            ->registerComponents()
            ->registerObservers()
            ->registerGates();
    }

    /**
     * Register components
     */
    private function registerComponents(): self
    {
        Blade::component('app-layout', \App\View\Components\AppLayout::class);

        return $this;
    }

    /**
     * Register observers
     */
    private function registerObservers(): self
    {
        Ride::observe(RideObserver::class);

        return $this;
    }

    /**
     * Register the application's gate definitions.
     */
    private function registerGates(): self
    {
        Gate::define('viewPulse', function (User $user) {
            return $user->email === 'jordan@partridge.rocks';
        });

        return $this;
    }
}
