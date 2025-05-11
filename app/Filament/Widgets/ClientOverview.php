<?php

namespace App\Filament\Widgets;

use App\Enums\ClientStatus;
use App\Models\Client;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientOverview extends BaseWidget
{
    protected static ?int $sort = 10;

    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getCardWidth(): ?string
    {
        return 'full';
    }

    protected function getStats(): array
    {
        $totalClients = Client::count();
        $leadCount = Client::where('status', ClientStatus::LEAD->value)->count();
        $activeCount = Client::where('status', ClientStatus::ACTIVE->value)->count();
        $formerCount = Client::where('status', ClientStatus::FORMER->value)->count();

        // Calculate percentages for chart
        $leadPercentage = $totalClients > 0 ? round(($leadCount / $totalClients) * 100) : 0;
        $activePercentage = $totalClients > 0 ? round(($activeCount / $totalClients) * 100) : 0;
        $formerPercentage = $totalClients > 0 ? round(($formerCount / $totalClients) * 100) : 0;

        return [
            Stat::make('Total Clients', $totalClients)
                ->description('Total clients in the system')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Leads', $leadCount)
                ->description($leadPercentage . '% of total clients')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),

            Stat::make('Active Clients', $activeCount)
                ->description($activePercentage . '% of total clients')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Former Clients', $formerCount)
                ->description($formerPercentage . '% of total clients')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('gray'),
        ];
    }
}
