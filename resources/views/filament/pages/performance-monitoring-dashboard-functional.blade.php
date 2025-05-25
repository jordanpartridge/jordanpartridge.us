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
            padding: 2rem;
            max-width: 80vw;
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>
    @endpush

    <div class="space-y-6">
        @php
            $healthScore = $this->getHealthScore();
            $stats = $this->getOverviewStats();
            $urlBreakdown = $this->getUrlBreakdown();
            $errorAnalysis = $this->getErrorAnalysis();
            $realTimeMetrics = $this->getRealTimeMetrics();
        @endphp

        <!-- Interactive Controls Bar -->
        <div class="flex flex-wrap gap-4 p-4 rounded-lg" style="background: rgba(255,255,255,0.05);">
            <!-- Time Range Filters -->
            <div class="flex gap-2">
                <span class="text-sm text-gray-400 self-center">Time Range:</span>
                @foreach (['1' => '1h', '6' => '6h', '24' => '24h', '168' => '7d', '720' => '30d'] as $hours => $label)
                    <button wire:click="$set('dateRange', '{{ $hours }}')"
                            class="filter-button {{ $dateRange == $hours ? 'active' : '' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Status Filters -->
            <div class="flex gap-2">
                <span class="text-sm text-gray-400 self-center">Status:</span>
                @foreach (['all' => 'All', 'success' => '2xx', 'errors' => '4xx+', 'slow' => 'Slow'] as $filter => $label)
                    <button wire:click="filterByStatus('{{ $filter }}')"
                            class="filter-button {{ $statusFilter == $filter ? 'active' : '' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Search -->
            <div class="flex-1 max-w-md">
                <input type="text"
                       wire:model.live.debounce.300ms="searchTerm"
                       placeholder="Search URLs..."
                       class="w-full px-3 py-2 bg-rgba(255,255,255,0.1) border border-rgba(255,255,255,0.2) rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Clear Filters -->
            <button wire:click="clearFilters" class="filter-button">
                🗑️ Clear
            </button>
        </div>

        <!-- Compact Health Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
        </div>

        <!-- URL Performance Table -->
        <div class="interactive-card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-white">🔍 URL Performance Analysis</h3>
                <div class="flex gap-2">
                    <button wire:click="sortBy('requests')"
                            class="filter-button {{ $sortBy == 'requests' ? 'active' : '' }}">
                        Sort by Requests {{ $sortBy == 'requests' ? ($sortDirection == 'asc' ? '↑' : '↓') : '' }}
                    </button>
                    <button wire:click="sortBy('avg_time')"
                            class="filter-button {{ $sortBy == 'avg_time' ? 'active' : '' }}">
                        Sort by Time {{ $sortBy == 'avg_time' ? ($sortDirection == 'asc' ? '↑' : '↓') : '' }}
                    </button>
                </div>
            </div>

            <div class="data-table">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('url')">URL</th>
                            <th wire:click="sortBy('requests')" class="text-center">Requests</th>
                            <th wire:click="sortBy('avg_time')" class="text-center">Avg Time</th>
                            <th class="text-center">Min/Max</th>
                            <th class="text-center">Error Rate</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($urlBreakdown as $url)
                        <tr>
                            <td class="font-mono text-sm">
                                <div class="truncate max-w-xs" title="{{ $url['url'] }}">{{ $url['url'] }}</div>
                            </td>
                            <td class="text-center">{{ number_format($url['requests']) }}</td>
                            <td class="text-center">
                                <span class="{{ $url['avg_time'] > 1000 ? 'text-red-400' : ($url['avg_time'] > 500 ? 'text-yellow-400' : 'text-green-400') }}">
                                    {{ $url['avg_time'] }}ms
                                </span>
                            </td>
                            <td class="text-center text-sm text-gray-400">
                                {{ $url['min_time'] }}ms / {{ $url['max_time'] }}ms
                            </td>
                            <td class="text-center">
                                @if ($url['error_rate'] > 0)
                                    <span class="status-badge status-500">{{ $url['error_rate'] }}%</span>
                                @else
                                    <span class="status-badge status-200">0%</span>
                                @endif
                            </td>
                            <td class="text-center">
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
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-white">⚡ Recent Requests</h3>
                <div class="text-sm text-gray-400">Auto-refreshing • Last: {{ date('H:i:s') }}</div>
            </div>

            <div class="data-table">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>URL</th>
                            <th>Status</th>
                            <th>Response Time</th>
                            <th>Memory</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->getFilteredMetrics()->limit(10)->get() as $metric)
                        <tr>
                            <td class="text-gray-400">{{ $metric->created_at->format('H:i:s') }}</td>
                            <td class="font-mono truncate max-w-xs" title="{{ $metric->url }}">{{ $metric->url }}</td>
                            <td>
                                <span class="status-badge status-{{ $metric->response_status >= 400 ? '500' : '200' }}">
                                    {{ $metric->response_status }}
                                </span>
                            </td>
                            <td class="{{ $metric->response_time > 1000 ? 'text-red-400' : ($metric->response_time > 500 ? 'text-yellow-400' : 'text-green-400') }}">
                                {{ $metric->response_time }}ms
                            </td>
                            <td class="text-gray-400">{{ round($metric->memory_usage / 1024 / 1024, 1) }}MB</td>
                            <td>
                                <button wire:click="viewRequest({{ $metric->id }})"
                                        class="text-blue-400 hover:text-blue-300">
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
</x-filament-panels::page>