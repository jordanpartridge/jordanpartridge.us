<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ClientDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.client-dashboard';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationLabel = 'Client Dashboard';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Client Management Dashboard';

    // Dashboard uses custom UI without widgets
}
