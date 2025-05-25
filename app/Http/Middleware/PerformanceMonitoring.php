<?php

namespace App\Http\Middleware;

use App\Models\PerformanceMetric;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitoring
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        // Track DB queries
        $queryCount = 0;
        $queryTime = 0;

        DB::listen(function ($query) use (&$queryCount, &$queryTime) {
            $queryCount++;
            $queryTime += $query->time;
        });

        // Track cache operations
        $cacheHits = 0;
        $cacheMisses = 0;

        Cache::macro('trackHit', function () use (&$cacheHits) {
            $cacheHits++;
        });

        Cache::macro('trackMiss', function () use (&$cacheMisses) {
            $cacheMisses++;
        });

        $response = $next($request);

        // Calculate metrics
        $endTime = microtime(true);
        $responseTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = memory_get_usage();
        $peakMemory = memory_get_peak_usage();

        // Get CPU usage if available (Linux/Unix)
        $cpuUsage = null;
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            $cpuUsage = $load[0] ?? null;
        }

        // Store metrics (async to avoid blocking response)
        if ($this->shouldTrack($request)) {
            // Use a job class instead of closure to avoid serialization issues
            $data = [
                'url'             => $request->fullUrl(),
                'method'          => $request->method(),
                'response_time'   => round($responseTime),
                'response_status' => $response->getStatusCode(),
                'memory_usage'    => $memoryUsage,
                'peak_memory'     => $peakMemory,
                'cpu_usage'       => $cpuUsage,
                'db_queries'      => $queryCount,
                'db_time'         => round($queryTime),
                'cache_hits'      => $cacheHits,
                'cache_misses'    => $cacheMisses,
                'user_agent'      => $request->userAgent(),
                'ip_address'      => $request->ip(),
                'user_id'         => auth()->id(),
                'additional_data' => [
                    'route'   => $request->route()?->getName(),
                    'ajax'    => $request->ajax(),
                    'referer' => $request->header('referer'),
                ],
            ];

            // For testing or sync queue, create directly to avoid serialization issues
            if (app()->environment('testing') || config('queue.default') === 'sync') {
                PerformanceMetric::create($data);
            } else {
                dispatch(function () use ($data) {
                    PerformanceMetric::create($data);
                })->afterResponse();
            }
        }

        // Add performance headers for debugging
        if (config('app.debug')) {
            $response->headers->set('X-Response-Time', round($responseTime) . 'ms');
            $response->headers->set('X-Memory-Usage', round($memoryUsage / 1024 / 1024, 2) . 'MB');
            $response->headers->set('X-DB-Queries', $queryCount);
        }

        return $response;
    }

    /**
     * Determine if the request should be tracked
     */
    protected function shouldTrack(Request $request): bool
    {
        // Skip static assets
        if ($request->is('css/*', 'js/*', 'images/*', 'fonts/*')) {
            return false;
        }

        // Skip health checks
        if ($request->is('health', 'up')) {
            return false;
        }

        // Skip API documentation
        if ($request->is('docs/*')) {
            return false;
        }

        return true;
    }
}
