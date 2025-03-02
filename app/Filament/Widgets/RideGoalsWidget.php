<?php

namespace App\Filament\Widgets;

use App\Models\Ride;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RideGoalsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm'      => '1/2',
    ];

    public function setColumnSpan(array|int|string $columnSpan): void
    {
        $this->columnSpan = $columnSpan;
    }

    // Override the base class column behavior
    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $weeklyTarget = 20; // miles

        // Get the start and end of the current week
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        $currentWeekDistance = Ride::query()
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->sum('distance') * 0.000621371;

        $progressPercentage = $weeklyTarget > 0 ? min(($currentWeekDistance / $weeklyTarget) * 100, 100) : 0;

        // Get last 7 days of rides for the chart
        $dailyDistances = Ride::query()
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(distance) as distance')
            ->groupByRaw('DATE(created_at)')  // Group by the actual date column, not the alias
            ->pluck('distance')
            ->toArray();

        return [
            Stat::make('Weekly Goal Progress', number_format($currentWeekDistance, 1) . '/' . $weeklyTarget . ' miles')
                ->description(number_format($progressPercentage, 1) . '% complete')
                ->chart($dailyDistances)
                ->color($progressPercentage >= 100 ? 'success' : 'info'),
        ];
    }

}
