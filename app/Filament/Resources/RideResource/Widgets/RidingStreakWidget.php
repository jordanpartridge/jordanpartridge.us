<?php

namespace App\Filament\Resources\RideResource\Widgets;

use App\Models\Ride;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RidingStreakWidget extends ChartWidget
{
    protected static ?string $heading = 'Weekly Distance (miles)';

    protected int|string|array $columnSpan = 'full';

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    // Add this method to make it collapsible
    protected function getHeaderActions(): array
    {
        return [
            $this->collapsible(),
        ];
    }

    protected function getData(): array
    {
        $now = now();
        $startDate = $now->copy()->subWeeks(7)->startOfWeek();
        $endDate = $now->copy()->endOfWeek();

        $rides = Ride::query()
            ->select('date', 'distance')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        $weeklyData = [];

        // Initialize weeks with zero
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
            $weekKey = $weekStart->format('Y-m-d');

            $weeklyData[$weekKey] = [
                'label' => $weekStart->format('M d') . '-' .
                    $weekStart->copy()->endOfWeek()->format('d'),
                'distance' => 0,
            ];
        }

        // Sum distances by week
        foreach ($rides as $ride) {
            $weekStart = Carbon::parse($ride->date)->startOfWeek()->format('Y-m-d');
            if (isset($weeklyData[$weekStart])) {
                $weeklyData[$weekStart]['distance'] += floatval($ride->distance);
            }
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Weekly Distance (miles)',
                    'data'            => array_map(fn ($data) => round($data['distance'], 1), $weeklyData),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => array_column($weeklyData, 'label'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
