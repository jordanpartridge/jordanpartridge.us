<?php

namespace App\Filament\Resources\RideResource\Widgets;

use App\Models\Ride;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RidingStreakWidget extends ChartWidget
{
    protected static ?string $heading = 'Weekly Distance (miles)';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $weeks = collect();
        $distances = collect();

        // Get last 8 weeks
        for ($i = 7; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();

            $weekDistance = Ride::whereBetween('date', [
                $startOfWeek,
                $endOfWeek
            ])->sum('distance');

            // Convert meters to miles
            $milesDistance = round($weekDistance * 0.000621371, 1);

            // Format date range like "Oct 23-29"
            $weekLabel = $startOfWeek->format('M d') . '-' . $endOfWeek->format('d');

            $weeks->push($weekLabel);
            $distances->push($milesDistance);
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Weekly Distance (miles)',
                    'data'            => $distances->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $weeks->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
