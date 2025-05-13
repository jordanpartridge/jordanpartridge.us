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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(GmailAuthServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS for URL generation when using Expose for callbacks
        if (str_contains(env('APP_URL', ''), 'expose') || str_contains(env('APP_URL', ''), 'https://') || app()->environment('production')) {
            URL::forceScheme('https');
        }

        $this
            ->registerObservers()
            ->registerGates()
            ->configureGmailClient();
    }

    /**
     * Configure Gmail Client with correct callback URL
     */
    private function configureGmailClient(): self
    {
        // Force the Gmail client redirect URI to match the actual application URL
        // This ensures the OAuth redirect works regardless of environment
        $callbackUrl = URL::to('/gmail/auth/callback');
        Config::set('gmail-client.redirect_uri', $callbackUrl);

        // Only log this in development or when running tests
        if (app()->environment(['local', 'testing'])) {
            Log::debug('Gmail client configured', [
                'redirect_uri'     => $callbackUrl,
                'client_id_exists' => !empty(config('gmail-client.client_id')),
            ]);
        }

        return $this;
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
