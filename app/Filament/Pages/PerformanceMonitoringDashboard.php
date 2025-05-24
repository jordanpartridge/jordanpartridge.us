<?php

namespace App\Filament\Pages;

use App\Models\PerformanceMetric;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class PerformanceMonitoringDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Performance Monitoring';

    protected static ?string $title = 'Performance Monitoring Dashboard';

    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.performance-monitoring-dashboard';

    public $dateRange = '24'; // hours

    public $selectedUrl = '';

    protected $queryString = ['dateRange', 'selectedUrl'];

    public function mount(): void
    {
        // Initialize data
    }

    public function getOverviewStats(): array
    {
        $metrics = PerformanceMetric::recent($this->dateRange);

        return [
            'average_response_time' => round($metrics->avg('response_time') ?? 0),
            'total_requests'        => $metrics->count(),
            'error_rate'            => $metrics->where('response_status', '>=', 400)->count() / max($metrics->count(), 1) * 100,
            'slow_requests'         => $metrics->slow()->count(),
            'average_memory'        => round($metrics->avg('memory_usage') / 1024 / 1024 ?? 0, 2),
            'total_db_queries'      => $metrics->sum('db_queries'),
        ];
    }

    public function getResponseTimeData(): array
    {
        $data = PerformanceMetric::recent($this->dateRange)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00") as hour, AVG(response_time) as avg_time, MAX(response_time) as max_time')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return [
            'labels'   => $data->pluck('hour')->toArray(),
            'datasets' => [
                [
                    'label'           => 'Average Response Time (ms)',
                    'data'            => $data->pluck('avg_time')->toArray(),
                    'borderColor'     => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label'           => 'Max Response Time (ms)',
                    'data'            => $data->pluck('max_time')->toArray(),
                    'borderColor'     => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ],
            ],
        ];
    }

    public function getTopSlowUrls(): array
    {
        return PerformanceMetric::recent($this->dateRange)
            ->select('url', DB::raw('AVG(response_time) as avg_time'), DB::raw('COUNT(*) as count'))
            ->groupBy('url')
            ->orderBy('avg_time', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'url'      => parse_url($item->url, PHP_URL_PATH) ?? $item->url,
                    'avg_time' => round($item->avg_time),
                    'count'    => $item->count,
                ];
            })
            ->toArray();
    }

    public function getErrorDistribution(): array
    {
        $errors = PerformanceMetric::recent($this->dateRange)
            ->select('response_status', DB::raw('COUNT(*) as count'))
            ->where('response_status', '>=', 400)
            ->groupBy('response_status')
            ->orderBy('count', 'desc')
            ->get();

        return [
            'labels'   => $errors->pluck('response_status')->toArray(),
            'datasets' => [
                [
                    'data'            => $errors->pluck('count')->toArray(),
                    'backgroundColor' => [
                        'rgb(239, 68, 68)',
                        'rgb(251, 146, 60)',
                        'rgb(250, 204, 21)',
                        'rgb(147, 51, 234)',
                        'rgb(59, 130, 246)',
                    ],
                ],
            ],
        ];
    }

    public function getDatabaseMetrics(): array
    {
        $data = PerformanceMetric::recent($this->dateRange)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00") as hour, AVG(db_queries) as avg_queries, AVG(db_time) as avg_time')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return [
            'labels'   => $data->pluck('hour')->toArray(),
            'datasets' => [
                [
                    'label'           => 'Average DB Queries',
                    'data'            => $data->pluck('avg_queries')->toArray(),
                    'borderColor'     => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'yAxisID'         => 'y',
                ],
                [
                    'label'           => 'Average DB Time (ms)',
                    'data'            => $data->pluck('avg_time')->toArray(),
                    'borderColor'     => 'rgb(168, 85, 247)',
                    'backgroundColor' => 'rgba(168, 85, 247, 0.1)',
                    'yAxisID'         => 'y1',
                ],
            ],
        ];
    }

    public function getMemoryUsageData(): array
    {
        $data = PerformanceMetric::recent($this->dateRange)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00") as hour, AVG(memory_usage) / 1024 / 1024 as avg_memory, MAX(peak_memory) / 1024 / 1024 as max_memory')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return [
            'labels'   => $data->pluck('hour')->toArray(),
            'datasets' => [
                [
                    'label'           => 'Average Memory Usage (MB)',
                    'data'            => $data->pluck('avg_memory')->map(fn ($val) => round($val, 2))->toArray(),
                    'borderColor'     => 'rgb(251, 146, 60)',
                    'backgroundColor' => 'rgba(251, 146, 60, 0.1)',
                ],
                [
                    'label'           => 'Peak Memory Usage (MB)',
                    'data'            => $data->pluck('max_memory')->map(fn ($val) => round($val, 2))->toArray(),
                    'borderColor'     => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ],
            ],
        ];
    }

    public function getCacheEfficiency(): array
    {
        $metrics = PerformanceMetric::recent($this->dateRange);
        $totalHits = $metrics->sum('cache_hits');
        $totalMisses = $metrics->sum('cache_misses');
        $hitRate = $totalHits + $totalMisses > 0 ? ($totalHits / ($totalHits + $totalMisses)) * 100 : 0;

        return [
            'hit_rate'     => round($hitRate, 1),
            'total_hits'   => $totalHits,
            'total_misses' => $totalMisses,
        ];
    }

    public function updateDateRange($range): void
    {
        $this->dateRange = $range;
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}
