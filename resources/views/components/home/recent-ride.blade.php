@props(['ride'])

@if ($ride)
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden transform transition-all duration-300 hover:shadow-md border border-gray-100 dark:border-gray-700">
    <div class="p-5">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 bg-primary-100 dark:bg-primary-900/50 p-2 rounded-full">
                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Ride</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Synced with Strava API</p>
            </div>
        </div>

        <div class="mb-4">
            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-lg mb-1">{{ $ride->name }}</h4>
            <div class="text-sm text-gray-600 dark:text-gray-300 mb-1">{{ $ride->date->format('F j, Y') }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $ride->ride_diff }}</div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="text-center px-2 py-3 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Distance</div>
                <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $ride->distance }} mi</div>
            </div>
            <div class="text-center px-2 py-3 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Elevation</div>
                <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $ride->elevation }} ft</div>
            </div>
            <div class="text-center px-2 py-3 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Moving Time</div>
                <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $ride->moving_time }}</div>
            </div>
            <div class="text-center px-2 py-3 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Avg Speed</div>
                <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $ride->average_speed }} mph</div>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                    <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs font-bold leading-none text-primary-100 bg-primary-700 rounded-full">API</span>
                    Synced automatically
                </span>
                <a href="/bike" class="text-primary-600 dark:text-primary-400 text-sm font-medium hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                    View All Rides →
                </a>
            </div>
        </div>
    </div>
</div>
@else
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 p-5">
    <div class="flex items-center justify-center h-48">
        <div class="text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">No Recent Rides</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ride data will appear here once synced with Strava</p>
        </div>
    </div>
</div>
@endif