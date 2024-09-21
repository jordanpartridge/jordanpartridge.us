<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                MenuItem::make()
                    ->label('Connect Strava')
                    ->url(route('strava:redirect'))
                    ->icon('heroicon-s-cog'),
                MenuItem::make()
                    ->label('Return to Home')
                    ->url('/'),
            ]);
        });
    }

}
