<x-filament-panels::page>
    @push('styles')
    <style>
        .interactive-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .interactive-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border-color: rgba(59, 130, 246, 0.5);
        }

        .filter-button {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: rgba(255,255,255,0.8);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .filter-button:hover, .filter-button.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
            color: white;
        }

        .data-table {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            overflow: hidden;
            overflow-x: auto;
        }

        .data-table th {
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .data-table th:hover {
            background: rgba(255,255,255,0.15);
        }

        .data-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .data-table tr:hover {
            background: rgba(255,255,255,0.05);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-200 { background: rgba(16, 185, 129, 0.2); color: #10b981; }
        .status-400 { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
        .status-500 { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

        .metric-trend {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .trend-up { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .trend-down { background: rgba(16, 185, 129, 0.2); color: #10b981; }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: linear-gradient(145deg, rgba(31, 41, 55, 0.95), rgba(17, 24, 39, 0.95));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            padding: 1rem;
            margin: 1rem;
            max-width: calc(100vw - 2rem);
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
            width: 100%;
        }

        @media (min-width: 640px) {
            .modal-content {
                padding: 2rem;
                margin: 2rem;
                max-width: 80vw;
                max-height: 80vh;
            }
        }
    </style>
    @endpush

    <div class="w-full max-w-none overflow-hidden">
    <div class="space-y-6">
        @php
            $healthScore = $this->getHealthScore();
            $stats = $this->getOverviewStats();
            $urlBreakdown = $this->getUrlBreakdown();
            $errorAnalysis = $this->getErrorAnalysis();
            $realTimeMetrics = $this->getRealTimeMetrics();
        @endphp

        <!-- Interactive Controls Bar -->
        <div class="bg-white/5 p-4 rounded-lg space-y-4">
            <!-- Time Range Filters -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm text-gray-400 whitespace-nowrap">Time Range:</span>
                <div class="flex flex-wrap gap-2">
                    @foreach (['1' => '1h', '6' => '6h', '24' => '24h', '168' => '7d', '720' => '30d'] as $hours => $label)
                        <button wire:click="$set('dateRange', '{{ $hours }}')"
                                class="filter-button {{ $dateRange == $hours ? 'active' : '' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Status Filters -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm text-gray-400 whitespace-nowrap">Status:</span>
                <div class="flex flex-wrap gap-2">
                    @foreach (['all' => 'All', 'success' => '2xx', 'errors' => '4xx+', 'slow' => 'Slow'] as $filter => $label)
                        <button wire:click="filterByStatus('{{ $filter }}')"
                                class="filter-button {{ $statusFilter == $filter ? 'active' : '' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Search and Clear -->
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="flex-1">
                    <input type="text"
                           wire:model.live.debounce.300ms="searchTerm"
                           placeholder="Search URLs..."
                           class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button wire:click="clearFilters" class="filter-button whitespace-nowrap">
                    🗑️ Clear
                </button>
            </div>
        </div>

        <!-- Compact Health Overview -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="interactive-card text-center" wire:click="$set('statusFilter', 'all')">
                <div class="text-2xl font-bold text-white">{{ $healthScore['score'] }}</div>
                <div class="text-sm text-gray-400">Health Score</div>
                <div class="text-xs mt-1 status-badge status-200">{{ ucfirst($healthScore['status']) }}</div>
            </div>

            <div class="interactive-card text-center" wire:click="filterByStatus('errors')">
                <div class="text-2xl font-bold {{ $stats['error_rate'] > 0 ? 'text-red-400' : 'text-green-400' }}">
                    {{ number_format($stats['error_rate'], 1) }}%
                </div>
                <div class="text-sm text-gray-400">Error Rate</div>
                <div class="text-xs mt-1">{{ $stats['total_requests'] }} requests</div>
            </div>

            <div class="interactive-card text-center" wire:click="filterByStatus('slow')">
                <div class="text-2xl font-bold {{ $stats['average_response_time'] > 500 ? 'text-yellow-400' : 'text-green-400' }}">
                    {{ $stats['average_response_time'] }}ms
                </div>
                <div class="text-sm text-gray-400">Avg Response</div>
                <div class="text-xs mt-1">{{ $stats['slow_requests'] }} slow</div>
            </div>

            <div class="interactive-card text-center">
                <div class="text-2xl font-bold text-blue-400">{{ $realTimeMetrics['active_requests'] }}</div>
                <div class="text-sm text-gray-400">Active (1m)</div>
                <div class="text-xs mt-1">{{ $realTimeMetrics['error_count'] }} errors</div>
            </div>

            <div class="interactive-card text-center">
                <div class="text-2xl font-bold text-purple-400">{{ number_format($stats['average_memory'], 1) }}MB</div>
                <div class="text-sm text-gray-400">Avg Memory</div>
                <div class="text-xs mt-1">{{ number_format($stats['total_db_queries']) }} queries</div>
            </div>

            @php $cacheEfficiency = $this->getCacheEfficiency(); @endphp
            <div class="interactive-card text-center">
                <div class="text-2xl font-bold {{ $cacheEfficiency['hit_rate'] > 80 ? 'text-green-400' : ($cacheEfficiency['hit_rate'] > 50 ? 'text-yellow-400' : 'text-red-400') }}">
                    {{ $cacheEfficiency['hit_rate'] }}%
                </div>
                <div class="text-sm text-gray-400">Cache Hit Rate</div>
                <div class="text-xs mt-1">{{ number_format($cacheEfficiency['total_hits']) }} hits</div>
            </div>
        </div>

        <!-- URL Performance Table -->
        <div class="interactive-card">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                <h3 class="text-lg font-semibold text-white">🔍 URL Performance Analysis</h3>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="sortBy('requests')"
                            class="filter-button text-sm {{ $sortBy == 'requests' ? 'active' : '' }}">
                        Requests {{ $sortBy == 'requests' ? ($sortDirection == 'asc' ? '↑' : '↓') : '' }}
                    </button>
                    <button wire:click="sortBy('avg_time')"
                            class="filter-button text-sm {{ $sortBy == 'avg_time' ? 'active' : '' }}">
                        Time {{ $sortBy == 'avg_time' ? ($sortDirection == 'asc' ? '↑' : '↓') : '' }}
                    </button>
                </div>
            </div>

            <div class="data-table">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('url')" class="min-w-0 w-1/3">URL</th>
                                <th wire:click="sortBy('requests')" class="text-center whitespace-nowrap">Requests</th>
                                <th wire:click="sortBy('avg_time')" class="text-center whitespace-nowrap">Avg Time</th>
                                <th class="text-center whitespace-nowrap hidden sm:table-cell">Min/Max</th>
                                <th class="text-center whitespace-nowrap">Error Rate</th>
                                <th class="text-center whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($urlBreakdown as $url)
                            <tr>
                                <td class="font-mono text-sm min-w-0">
                                    <div class="truncate max-w-[200px] sm:max-w-xs" title="{{ $url['url'] }}">{{ $url['url'] }}</div>
                                </td>
                                <td class="text-center whitespace-nowrap">{{ number_format($url['requests']) }}</td>
                                <td class="text-center whitespace-nowrap">
                                    <span class="{{ $url['avg_time'] > 1000 ? 'text-red-400' : ($url['avg_time'] > 500 ? 'text-yellow-400' : 'text-green-400') }}">
                                        {{ $url['avg_time'] }}ms
                                    </span>
                                </td>
                                <td class="text-center text-sm text-gray-400 whitespace-nowrap hidden sm:table-cell">
                                    {{ $url['min_time'] }}ms / {{ $url['max_time'] }}ms
                                </td>
                                <td class="text-center whitespace-nowrap">
                                    @if ($url['error_rate'] > 0)
                                        <span class="status-badge status-500">{{ $url['error_rate'] }}%</span>
                                    @else
                                        <span class="status-badge status-200">0%</span>
                                    @endif
                                </td>
                                <td class="text-center whitespace-nowrap">
                                    <button wire:click="$set('selectedUrl', '{{ addslashes($url['url']) }}')"
                                            class="text-blue-400 hover:text-blue-300 text-sm">
                                        Filter
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-400">
                                    No data found for current filters
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Error Analysis -->
        @if (count($errorAnalysis) > 0)
        <div class="interactive-card">
            <h3 class="text-lg font-semibold text-white mb-4">🚨 Error Analysis</h3>
            <div class="grid gap-4">
                @foreach ($errorAnalysis as $errorGroup)
                <div class="p-4 rounded-lg" style="background: rgba(239, 68, 68, 0.1);">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <span class="text-lg font-bold text-red-400">{{ $errorGroup['status'] }}</span>
                            <span class="text-sm text-gray-400 ml-2">{{ $errorGroup['total'] }} occurrences</span>
                        </div>
                        <button wire:click="filterByStatus('errors')" class="filter-button text-sm">
                            View All
                        </button>
                    </div>
                    <div class="space-y-2">
                        @foreach ($errorGroup['urls'] as $urlError)
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-mono truncate max-w-md" title="{{ $urlError['url'] }}">{{ $urlError['url'] }}</span>
                            <div class="flex gap-4 text-gray-400">
                                <span>{{ $urlError['count'] }}x</span>
                                <span>{{ $urlError['avg_time'] }}ms</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Requests (Real-time) -->
        <div class="interactive-card">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-4">
                <h3 class="text-lg font-semibold text-white">⚡ Recent Requests</h3>
                <div class="text-sm text-gray-400">Auto-refreshing • Last: {{ date('H:i:s') }}</div>
            </div>

            <div class="data-table">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-full">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">Time</th>
                                <th class="min-w-0 w-1/3">URL</th>
                                <th class="whitespace-nowrap">Status</th>
                                <th class="whitespace-nowrap">Response Time</th>
                                <th class="whitespace-nowrap hidden sm:table-cell">Memory</th>
                                <th class="whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->getFilteredMetrics()->limit(10)->get() as $metric)
                            <tr>
                                <td class="text-gray-400 whitespace-nowrap">{{ $metric->created_at->format('H:i:s') }}</td>
                                <td class="font-mono text-sm min-w-0">
                                    <div class="truncate max-w-[200px] sm:max-w-xs" title="{{ $metric->url }}">{{ $metric->url }}</div>
                                </td>
                                <td class="whitespace-nowrap">
                                    <span class="status-badge status-{{ $metric->response_status >= 400 ? '500' : '200' }}">
                                        {{ $metric->response_status }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap {{ $metric->response_time > 1000 ? 'text-red-400' : ($metric->response_time > 500 ? 'text-yellow-400' : 'text-green-400') }}">
                                    {{ $metric->response_time }}ms
                                </td>
                                <td class="text-gray-400 whitespace-nowrap hidden sm:table-cell">{{ round($metric->memory_usage / 1024 / 1024, 1) }}MB</td>
                                <td class="whitespace-nowrap">
                                    <button wire:click="viewRequest({{ $metric->id }})"
                                            class="text-blue-400 hover:text-blue-300 text-sm">
                                        Details
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Details Modal -->
    @if ($selectedMetricId)
    @php $trace = $this->getRequestTrace($selectedMetricId); @endphp
    <div class="modal-overlay" wire:click="$set('selectedMetricId', null)">
        <div class="modal-content" wire:click.stop>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">🔍 Request Trace Details</h3>
                <button wire:click="$set('selectedMetricId', null)" class="text-gray-400 hover:text-white text-xl">×</button>
            </div>

            @if ($trace)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-white mb-3">Request Info</h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-400">URL:</span> <span class="font-mono">{{ $trace['url'] }}</span></div>
                        <div><span class="text-gray-400">Method:</span> <span class="font-mono">{{ $trace['method'] }}</span></div>
                        <div><span class="text-gray-400">Status:</span>
                            <span class="status-badge status-{{ $trace['response_status'] >= 400 ? '500' : '200' }}">
                                {{ $trace['response_status'] }}
                            </span>
                        </div>
                        <div><span class="text-gray-400">Time:</span> {{ $trace['created_at']->format('Y-m-d H:i:s') }}</div>
                        <div><span class="text-gray-400">IP:</span> {{ $trace['ip_address'] }}</div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-white mb-3">Performance Metrics</h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-400">Response Time:</span>
                            <span class="{{ $trace['response_time'] > 1000 ? 'text-red-400' : 'text-green-400' }}">
                                {{ $trace['response_time'] }}ms
                            </span>
                        </div>
                        <div><span class="text-gray-400">Memory Usage:</span> {{ $trace['memory_usage'] }}MB</div>
                        <div><span class="text-gray-400">Peak Memory:</span> {{ $trace['peak_memory'] }}MB</div>
                        <div><span class="text-gray-400">DB Queries:</span> {{ $trace['db_queries'] }}</div>
                        <div><span class="text-gray-400">DB Time:</span> {{ $trace['db_time'] }}ms</div>
                        <div><span class="text-gray-400">Cache Hits:</span> {{ $trace['cache_hits'] }}</div>
                        <div><span class="text-gray-400">Cache Misses:</span> {{ $trace['cache_misses'] }}</div>
                    </div>
                </div>

                @if ($trace['additional_data'])
                <div class="md:col-span-2">
                    <h4 class="font-semibold text-white mb-3">Additional Data</h4>
                    <pre class="bg-gray-800 p-3 rounded text-xs overflow-x-auto">{{ json_encode($trace['additional_data'], JSON_PRETTY_PRINT) }}</pre>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        // Auto-refresh every 30 seconds
        setInterval(function() {
            @this.call('$refresh');
        }, 30000);

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                @this.set('selectedMetricId', null);
            }
        });
    </script>
    @endpush
    </div>
</x-filament-panels::page>