<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;

class ClientStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Client Distribution';

    protected static ?string $description = 'Breakdown of clients by status';

    protected static ?int $sort = 20;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Client::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Ensure all status types are represented even if count is 0
        $statuses = ['lead', 'active', 'former'];
        foreach ($statuses as $status) {
            if (!array_key_exists($status, $data)) {
                $data[$status] = 0;
            }
        }

        // Sort by status key to ensure consistent order
        ksort($data);

        return [
            'datasets' => [
                [
                    'label'           => 'Clients by Status',
                    'data'            => array_values($data),
                    'backgroundColor' => [
                        '#f59e0b', // warning - Lead
                        '#10b981', // success - Active
                        '#6b7280', // gray - Former
                    ],
                    'borderColor'  => '#ffffff',
                    'borderWidth'  => 2,
                    'hoverOffset'  => 8,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => array_map(function ($status) {
                // Handle the case where status might be an array key
                return is_string($status) ? ucfirst($status) : '';
            }, array_keys($data)),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels'   => [
                        'usePointStyle' => true,
                        'padding'       => 20,
                        'font'          => [
                            'size' => 12,
                        ],
                    ],
                ],
                'tooltip' => [
                    'displayColors' => false,
                ],
            ],
            'cutout' => '70%',
        ];
    }
}
