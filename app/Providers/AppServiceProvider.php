<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Ride;
use App\Models\User;
use App\Observers\PostObserver;
use App\Observers\RideObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this
            ->registerObservers()
            ->registerGates();
    }

    /**
     * Register observers
     */
    private function registerObservers(): self
    {
        Ride::observe(RideObserver::class);
        Post::observe(PostObserver::class);

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
