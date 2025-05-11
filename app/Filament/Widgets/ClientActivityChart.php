<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ClientActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Client Activity';

    protected static ?string $description = 'Client activity over the last 30 days';

    protected static ?int $sort = 40;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get the date range (last 30 days)
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->endOfDay();

        // Create a map of dates for the last 30 days
        $period = CarbonPeriod::create($startDate, '1 day', $endDate);
        $dates = collect($period)->mapWithKeys(function ($date) {
            return [$date->format('Y-m-d') => 0];
        });

        // Get client last contact dates
        $contacts = Client::query()
            ->where('last_contact_at', '>=', $startDate)
            ->where('last_contact_at', '<=', $endDate)
            ->select(
                DB::raw('DATE(last_contact_at) as date'),
                DB::raw('count(*) as count')
            )
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Get client creation dates
        $creations = Client::query()
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Merge contacts and creations with the dates map
        $contactData = $dates->toArray();
        $creationData = $dates->toArray();

        foreach ($contacts as $date => $count) {
            if (isset($contactData[$date])) {
                $contactData[$date] = $count;
            }
        }

        foreach ($creations as $date => $count) {
            if (isset($creationData[$date])) {
                $creationData[$date] = $count;
            }
        }

        // Format dates for display (just show day/month)
        $formattedDates = $dates->keys()->map(function ($date) {
            return Carbon::parse($date)->format('d/m');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Client Contacts',
                    'data'            => array_values($contactData),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)', // blue-500 with transparency
                    'borderColor'     => 'rgb(59, 130, 246)', // blue-500
                    'tension'         => 0.3,
                    'borderWidth'     => 2,
                ],
                [
                    'label'           => 'New Clients',
                    'data'            => array_values($creationData),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)', // green-500 with transparency
                    'borderColor'     => 'rgb(16, 185, 129)', // green-500
                    'tension'         => 0.3,
                    'borderWidth'     => 2,
                ],
            ],
            'labels' => $formattedDates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'align'    => 'start',
                ],
                'tooltip' => [
                    'mode'      => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => [
                        'precision' => 0,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'elements' => [
                'point' => [
                    'radius'      => 2,
                    'hoverRadius' => 4,
                ],
            ],
            'interaction' => [
                'mode'      => 'index',
                'intersect' => false,
            ],
            'responsive'          => true,
            'maintainAspectRatio' => false,
        ];
    }
}
