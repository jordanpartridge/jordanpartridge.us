<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Post;
use App\Models\Ride;
use App\Models\User;
use App\Observers\ClientObserver;
use App\Observers\PostObserver;
use App\Observers\RideObserver;
use App\Observers\UserObserver;
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
        Client::observe(ClientObserver::class);
        Post::observe(PostObserver::class);
        Ride::observe(RideObserver::class);
        User::observe(UserObserver::class);

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
