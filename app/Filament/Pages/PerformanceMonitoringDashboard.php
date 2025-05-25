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

    protected static string $view = 'filament.pages.performance-monitoring-dashboard-functional';

    public $dateRange = '24'; // hours
    public $selectedUrl = '';
    public $statusFilter = 'all';
    public $sortBy = 'response_time';
    public $sortDirection = 'desc';
    public $searchTerm = '';
    public $showOnlyErrors = false;
    public $selectedMetricId = null;

    protected $queryString = [
        'dateRange',
        'selectedUrl',
        'statusFilter',
        'sortBy',
        'sortDirection',
        'searchTerm',
        'showOnlyErrors'
    ];

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

    /**
     * Calculate overall system health score (0-100)
     * Based on response time, error rate, and resource usage
     */
    public function getHealthScore(): array
    {
        $stats = $this->getOverviewStats();

        // Response time score (0-40 points)
        $responseScore = min(40, max(0, 40 - ($stats['average_response_time'] / 25)));

        // Error rate score (0-30 points)
        $errorScore = max(0, 30 - ($stats['error_rate'] * 6));

        // Memory efficiency score (0-20 points)
        $memoryScore = min(20, max(0, 20 - ($stats['average_memory'] / 10)));

        // Database efficiency score (0-10 points)
        $dbScore = min(10, max(0, 10 - ($stats['total_db_queries'] / $stats['total_requests'] ?? 1)));

        $totalScore = round($responseScore + $errorScore + $memoryScore + $dbScore);

        return [
            'score'     => $totalScore,
            'status'    => $this->getHealthStatus($totalScore),
            'message'   => $this->getHealthMessage($totalScore, $stats),
            'breakdown' => [
                'response' => round($responseScore),
                'errors'   => round($errorScore),
                'memory'   => round($memoryScore),
                'database' => round($dbScore),
            ]
        ];
    }

    /**
     * Get smart insights about performance trends
     */
    public function getSmartInsights(): array
    {
        $currentStats = $this->getOverviewStats();
        $previousStats = $this->getPreviousPeriodStats();

        $insights = [];

        // Response time trend
        $responseChange = $this->calculatePercentageChange(
            $previousStats['average_response_time'],
            $currentStats['average_response_time']
        );

        if (abs($responseChange) > 10) {
            $insights[] = [
                'type'    => $responseChange > 0 ? 'warning' : 'success',
                'title'   => $responseChange > 0 ? 'Response Time Increased' : 'Response Time Improved',
                'message' => sprintf(
                    'Average response time %s by %s%% compared to the previous period.',
                    $responseChange > 0 ? 'increased' : 'decreased',
                    abs($responseChange)
                ),
                'value' => $responseChange
            ];
        }

        // Error rate trend
        $errorChange = $this->calculatePercentageChange(
            $previousStats['error_rate'],
            $currentStats['error_rate']
        );

        if (abs($errorChange) > 20) {
            $insights[] = [
                'type'    => $errorChange > 0 ? 'danger' : 'success',
                'title'   => $errorChange > 0 ? 'Error Rate Spike' : 'Error Rate Improved',
                'message' => sprintf(
                    'Error rate %s by %s%% - %s',
                    $errorChange > 0 ? 'increased' : 'decreased',
                    abs($errorChange),
                    $errorChange > 0 ? 'requires immediate attention' : 'great improvement!'
                ),
                'value' => $errorChange
            ];
        }

        return $insights;
    }

    /**
     * Get filtered metrics based on current filters
     */
    public function getFilteredMetrics()
    {
        $query = PerformanceMetric::recent($this->dateRange);

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            switch ($this->statusFilter) {
                case 'success':
                    $query->whereBetween('response_status', [200, 299]);
                    break;
                case 'errors':
                    $query->where('response_status', '>=', 400);
                    break;
                case 'slow':
                    $query->slow();
                    break;
            }
        }

        // Apply URL filter
        if ($this->selectedUrl) {
            $query->where('url', 'like', '%' . $this->selectedUrl . '%');
        }

        // Apply search
        if ($this->searchTerm) {
            $query->where('url', 'like', '%' . $this->searchTerm . '%');
        }

        // Apply error filter
        if ($this->showOnlyErrors) {
            $query->where('response_status', '>=', 400);
        }

        return $query->orderBy($this->sortBy, $this->sortDirection);
    }

    /**
     * Get detailed error analysis
     */
    public function getErrorAnalysis(): array
    {
        $errors = PerformanceMetric::recent($this->dateRange)
            ->where('response_status', '>=', 400)
            ->selectRaw('response_status, COUNT(*) as count, AVG(response_time) as avg_time, url')
            ->groupBy('response_status', 'url')
            ->orderBy('count', 'desc')
            ->get();

        $grouped = $errors->groupBy('response_status')->map(function ($group, $status) {
            return [
                'status' => $status,
                'total'  => $group->sum('count'),
                'urls'   => $group->map(function ($item) {
                    return [
                        'url'      => $item->url,
                        'count'    => $item->count,
                        'avg_time' => round($item->avg_time)
                    ];
                })->take(5)->values()
            ];
        });

        return $grouped->values()->toArray();
    }

    /**
     * Get request trace details for a specific metric
     */
    public function getRequestTrace($metricId): ?array
    {
        $metric = PerformanceMetric::find($metricId);

        if (!$metric) {
            return null;
        }

        return [
            'id'              => $metric->id,
            'url'             => $metric->url,
            'method'          => $metric->method,
            'response_time'   => $metric->response_time,
            'response_status' => $metric->response_status,
            'memory_usage'    => round($metric->memory_usage / 1024 / 1024, 2),
            'peak_memory'     => round($metric->peak_memory / 1024 / 1024, 2),
            'db_queries'      => $metric->db_queries,
            'db_time'         => $metric->db_time,
            'cache_hits'      => $metric->cache_hits,
            'cache_misses'    => $metric->cache_misses,
            'user_agent'      => $metric->user_agent,
            'ip_address'      => $metric->ip_address,
            'created_at'      => $metric->created_at,
            'additional_data' => $metric->additional_data
        ];
    }

    /**
     * Get URL performance breakdown
     */
    public function getUrlBreakdown(): array
    {
        $baseQuery = PerformanceMetric::recent($this->dateRange);

        // Apply filters
        if ($this->statusFilter !== 'all') {
            switch ($this->statusFilter) {
                case 'success':
                    $baseQuery->whereBetween('response_status', [200, 299]);
                    break;
                case 'errors':
                    $baseQuery->where('response_status', '>=', 400);
                    break;
                case 'slow':
                    $baseQuery->slow();
                    break;
            }
        }

        if ($this->selectedUrl) {
            $baseQuery->where('url', 'like', '%' . $this->selectedUrl . '%');
        }

        if ($this->searchTerm) {
            $baseQuery->where('url', 'like', '%' . $this->searchTerm . '%');
        }

        if ($this->showOnlyErrors) {
            $baseQuery->where('response_status', '>=', 400);
        }

        $results = $baseQuery
            ->selectRaw('url, COUNT(*) as requests, AVG(response_time) as avg_time, MAX(response_time) as max_time, MIN(response_time) as min_time, SUM(CASE WHEN response_status >= 400 THEN 1 ELSE 0 END) as errors')
            ->groupBy('url')
            ->orderByRaw($this->sortBy === 'requests' ? 'requests ' . $this->sortDirection : 'avg_time ' . $this->sortDirection)
            ->limit(20)
            ->get();

        return $results->map(function ($item) {
            return [
                'url'        => $item->url,
                'requests'   => $item->requests,
                'avg_time'   => round($item->avg_time),
                'max_time'   => round($item->max_time),
                'min_time'   => round($item->min_time),
                'errors'     => $item->errors,
                'error_rate' => $item->requests > 0 ? round(($item->errors / $item->requests) * 100, 1) : 0
            ];
        })->toArray();
    }

    /**
     * Get real-time metrics (last 5 minutes)
     */
    public function getRealTimeMetrics(): array
    {
        $metrics = PerformanceMetric::where('created_at', '>=', now()->subMinutes(5))->get();

        return [
            'active_requests'   => $metrics->where('created_at', '>=', now()->subMinutes(1))->count(),
            'avg_response_time' => round($metrics->avg('response_time') ?? 0),
            'error_count'       => $metrics->where('response_status', '>=', 400)->count(),
            'last_updated'      => now()->toISOString()
        ];
    }

    /**
     * Livewire action: Filter by status
     */
    public function filterByStatus($status)
    {
        $this->statusFilter = $status;
        $this->selectedMetricId = null;
    }

    /**
     * Livewire action: Sort by column
     */
    public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'desc';
        }
    }

    /**
     * Livewire action: View request details
     */
    public function viewRequest($metricId)
    {
        $this->selectedMetricId = $metricId;
    }

    /**
     * Livewire action: Clear filters
     */
    public function clearFilters()
    {
        $this->statusFilter = 'all';
        $this->selectedUrl = '';
        $this->searchTerm = '';
        $this->showOnlyErrors = false;
        $this->selectedMetricId = null;
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

    /**
     * Get health status based on score
     */
    protected function getHealthStatus(int $score): string
    {
        if ($score >= 85) {
            return 'excellent';
        }
        if ($score >= 70) {
            return 'good';
        }
        if ($score >= 50) {
            return 'fair';
        }
        return 'poor';
    }

    /**
     * Generate intelligent health message
     */
    protected function getHealthMessage(int $score, array $stats): string
    {
        if ($score >= 85) {
            return "Your application is performing excellently. Response times are optimal.";
        }

        if ($score >= 70) {
            return "Good performance overall. Minor optimizations could boost efficiency.";
        }

        if ($stats['error_rate'] > 5) {
            return "Elevated error rate detected. Investigate failing requests immediately.";
        }

        if ($stats['average_response_time'] > 1000) {
            return "Slow response times detected. Consider optimizing database queries.";
        }

        return "Performance needs attention. Review slowest endpoints and error patterns.";
    }

    /**
     * Get stats for previous period for comparison
     */
    protected function getPreviousPeriodStats(): array
    {
        $endDate = now()->subHours($this->dateRange);
        $startDate = $endDate->copy()->subHours($this->dateRange);

        $metrics = PerformanceMetric::whereBetween('created_at', [$startDate, $endDate]);

        return [
            'average_response_time' => round($metrics->avg('response_time') ?? 0),
            'total_requests'        => $metrics->count(),
            'error_rate'            => $metrics->where('response_status', '>=', 400)->count() / max($metrics->count(), 1) * 100,
            'average_memory'        => round($metrics->avg('memory_usage') / 1024 / 1024 ?? 0, 2),
        ];
    }

    /**
     * Calculate percentage change between two values
     */
    protected function calculatePercentageChange(float $old, float $new): float
    {
        if ($old == 0) {
            return $new > 0 ? 100 : 0;
        }
        return round((($new - $old) / $old) * 100, 1);
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}
