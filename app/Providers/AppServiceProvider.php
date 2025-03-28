<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the AIServiceProvider
        $this->app->register(AIServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Livewire Components
        Livewire::component('social-preview-modal', \App\Livewire\SocialPreviewModal::class);
    }
}
