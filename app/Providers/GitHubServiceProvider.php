<?php

namespace App\Providers;

use App\Settings\GitHubSettings;
use JordanPartridge\GithubClient\Github;
use JordanPartridge\GithubClient\GithubConnector;
use JordanPartridge\GithubClient\Contracts\GithubConnectorInterface;
use Illuminate\Support\ServiceProvider;

class GitHubServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the GitHub connector
        $this->app->singleton(GithubConnectorInterface::class, function ($app) {
            $settings = $app->make(GitHubSettings::class);
            return new GithubConnector($settings->getToken());
        });

        // Register the GitHub client
        $this->app->singleton(Github::class, function ($app) {
            return new Github(
                $app->make(GithubConnectorInterface::class)
            );
        });

        // Register facades
        $this->app->alias(Github::class, 'github');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
