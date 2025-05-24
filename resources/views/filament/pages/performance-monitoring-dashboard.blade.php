<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Date Range Selector -->
        <div class="flex justify-end">
            <select wire:model.live="dateRange" class="filament-select-input text-sm">
                <option value="1">Last Hour</option>
                <option value="6">Last 6 Hours</option>
                <option value="12">Last 12 Hours</option>
                <option value="24">Last 24 Hours</option>
                <option value="48">Last 48 Hours</option>
                <option value="168">Last Week</option>
            </select>
        </div>

        @php
            $stats = $this->getOverviewStats();
            $responseTimeData = $this->getResponseTimeData();
            $slowUrls = $this->getTopSlowUrls();
            $errorData = $this->getErrorDistribution();
            $dbMetrics = $this->getDatabaseMetrics();
            $memoryData = $this->getMemoryUsageData();
            $cacheStats = $this->getCacheEfficiency();
        @endphp

        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            <x-filament::card>
                <div class="text-sm text-gray-500">Avg Response Time</div>
                <div class="text-2xl font-bold">{{ $stats['average_response_time'] }}ms</div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-sm text-gray-500">Total Requests</div>
                <div class="text-2xl font-bold">{{ number_format($stats['total_requests']) }}</div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-sm text-gray-500">Error Rate</div>
                <div class="text-2xl font-bold text-danger-600">{{ number_format($stats['error_rate'], 1) }}%</div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-sm text-gray-500">Slow Requests</div>
                <div class="text-2xl font-bold text-warning-600">{{ $stats['slow_requests'] }}</div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-sm text-gray-500">Avg Memory</div>
                <div class="text-2xl font-bold">{{ $stats['average_memory'] }}MB</div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-sm text-gray-500">Total DB Queries</div>
                <div class="text-2xl font-bold">{{ number_format($stats['total_db_queries']) }}</div>
            </x-filament::card>
        </div>

        <!-- Response Time Chart -->
        <x-filament::card>
            <h3 class="text-lg font-medium mb-4">Response Time Trends</h3>
            <canvas id="responseTimeChart" height="100"></canvas>
        </x-filament::card>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Slow URLs -->
            <x-filament::card>
                <h3 class="text-lg font-medium mb-4">Slowest Endpoints</h3>
                <div class="space-y-2">
                    @foreach ($slowUrls as $url)
                        <div class="flex justify-between items-center">
                            <div class="flex-1 mr-4">
                                <div class="text-sm font-medium truncate">{{ $url['url'] }}</div>
                                <div class="text-xs text-gray-500">{{ $url['count'] }} requests</div>
                            </div>
                            <div class="text-sm font-bold text-danger-600">{{ $url['avg_time'] }}ms</div>
                        </div>
                    @endforeach
                </div>
            </x-filament::card>

            <!-- Error Distribution -->
            <x-filament::card>
                <h3 class="text-lg font-medium mb-4">Error Distribution</h3>
                <canvas id="errorChart" height="200"></canvas>
            </x-filament::card>
        </div>

        <!-- Database Metrics -->
        <x-filament::card>
            <h3 class="text-lg font-medium mb-4">Database Performance</h3>
            <canvas id="databaseChart" height="100"></canvas>
        </x-filament::card>

        <!-- Memory Usage -->
        <x-filament::card>
            <h3 class="text-lg font-medium mb-4">Memory Usage</h3>
            <canvas id="memoryChart" height="100"></canvas>
        </x-filament::card>

        <!-- Cache Efficiency -->
        <x-filament::card>
            <h3 class="text-lg font-medium mb-4">Cache Efficiency</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <div class="text-sm text-gray-500">Hit Rate</div>
                    <div class="text-2xl font-bold text-success-600">{{ $cacheStats['hit_rate'] }}%</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Total Hits</div>
                    <div class="text-2xl font-bold">{{ number_format($cacheStats['total_hits']) }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Total Misses</div>
                    <div class="text-2xl font-bold text-danger-600">{{ number_format($cacheStats['total_misses']) }}</div>
                </div>
            </div>
        </x-filament::card>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Response Time Chart
        const responseTimeCtx = document.getElementById('responseTimeChart').getContext('2d');
        new Chart(responseTimeCtx, {
            type: 'line',
            data: {!! json_encode($responseTimeData) !!},
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Response Time (ms)' }
                    }
                }
            }
        });

        // Error Distribution Chart
        const errorCtx = document.getElementById('errorChart').getContext('2d');
        new Chart(errorCtx, {
            type: 'doughnut',
            data: {!! json_encode($errorData) !!},
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });

        // Database Metrics Chart
        const dbCtx = document.getElementById('databaseChart').getContext('2d');
        new Chart(dbCtx, {
            type: 'line',
            data: {!! json_encode($dbMetrics) !!},
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Queries' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Time (ms)' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });

        // Memory Usage Chart
        const memoryCtx = document.getElementById('memoryChart').getContext('2d');
        new Chart(memoryCtx, {
            type: 'line',
            data: {!! json_encode($memoryData) !!},
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Memory (MB)' }
                    }
                }
            }
        });
    </script>
    @endpush
</x-filament-panels::page>