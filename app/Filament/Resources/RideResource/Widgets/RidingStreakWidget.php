<?php

namespace App\Filament\Resources\RideResource\Widgets;

use App\Models\Ride;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RidingStreakWidget extends ChartWidget
{
    protected static ?string $heading = 'Weekly Distance & Progress';

    protected static ?string $pollingInterval = '30s';

    protected int|string|array $columnSpan = 'full';

    protected float $maxDistance = 0;
    protected float $avgDistance = 0;

    public function getDescription(): ?string
    {
        return "Average: {$this->avgDistance} miles/week | Peak: {$this->maxDistance} miles";
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive'          => true,
            'interaction'         => [
                'mode'      => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'bottom',
                    'labels'   => [
                        'font' => [
                            'size'   => 12,
                            'family' => 'Arial, sans-serif',
                        ],
                        'color' => '#555',
                    ],
                ],
                'tooltip' => [
                    'enabled'         => true,
                    'backgroundColor' => '#fff',
                    'borderColor'     => '#ddd',
                    'borderWidth'     => 1,
                    'titleColor'      => '#333',
                    'bodyColor'       => '#555',
                    'callbacks'       => [
                        'label' => "function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y + ' miles';
                            return label;
                        }",
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'ticks' => [
                        'font' => [
                            'size'   => 12,
                            'family' => 'Arial, sans-serif',
                        ],
                        'color' => '#666',
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'grid'        => [
                        'color'     => '#eee',
                        'lineWidth' => 0.5,
                    ],
                    'ticks' => [
                        'font' => [
                            'size'   => 12,
                            'family' => 'Arial, sans-serif',
                        ],
                        'color'    => '#666',
                        'callback' => "function(value) {
                            return value + ' mi';
                        }",
                    ],
                ],
            ],
            'layout' => [
                'padding' => [
                    'bottom' => 30,
                ],
            ],
            'animations' => [
                'tension' => [
                    'duration' => 800,
                    'easing'   => 'easeOutBounce',
                ],
            ],
        ];
    }

    protected function getHeight(): int|string|null
    {
        return 400;
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->collapsible(),
        ];
    }

    protected function getData(): array
    {
        $now = now();
        $startDate = $now->copy()->subWeeks(14)->startOfWeek();
        $endDate = $now->copy()->endOfWeek();

        $rides = Ride::query()
            ->select('date', 'distance')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        $weeks = [];
        $distances = [];
        $targets = [];
        $trends = [];

        $weeklyTarget = 20;
        $movingAverageWindow = 3;

        for ($i = 14; $i >= 0; $i--) {
            $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            $weeks[] = $weekStart->format('M d') . '-' . $weekEnd->format('d');
            $distances[] = 0;
            $targets[] = $weeklyTarget;

            $weekKey = $weekStart->format('Y-m-d');

            foreach ($rides as $ride) {
                $rideWeekStart = Carbon::parse($ride->date)->startOfWeek()->format('Y-m-d');
                if ($rideWeekStart === $weekKey) {
                    $distances[count($distances) - 1] += floatval($ride->distance);
                }
            }

            $distances[count($distances) - 1] = round($distances[count($distances) - 1], 1);
            $this->maxDistance = max($this->maxDistance, $distances[count($distances) - 1]);
        }

        for ($i = 0; $i < count($distances); $i++) {
            if ($i < $movingAverageWindow - 1) {
                $trends[] = null;
            } else {
                $sum = 0;
                for ($j = 0; $j < $movingAverageWindow; $j++) {
                    $sum += $distances[$i - $j];
                }
                $trends[] = round($sum / $movingAverageWindow, 1);
            }
        }

        $this->avgDistance = round(array_sum($distances) / count(array_filter($distances)), 1);

        return [
            'datasets' => [
                [
                    'label'                => 'Weekly Distance',
                    'data'                 => $distances,
                    'backgroundColor'      => 'rgba(59, 130, 246, 0.8)',
                    'hoverBackgroundColor' => 'rgba(37, 99, 235, 1)',
                    'borderRadius'         => 5,
                ],
                [
                    'label'       => 'Target',
                    'data'        => $targets,
                    'type'        => 'line',
                    'borderColor' => '#ef4444',
                    'borderDash'  => [5, 5],
                    'borderWidth' => 2,
                ],
                [
                    'label'       => '3-Week Trend',
                    'data'        => $trends,
                    'type'        => 'line',
                    'borderColor' => '#22c55e',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $weeks,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
