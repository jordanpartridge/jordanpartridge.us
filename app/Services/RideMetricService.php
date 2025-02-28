<?php

namespace App\Services;

use App\Models\Ride;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class RideMetricService
{
    /**
     * Cache TTL in seconds (1 hour)
     */
    protected const CACHE_TTL = 3600;

    /**
     * Calculate ride metrics for a given date range
     * Uses Redis caching to improve performance
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function calculateRideMetrics($startDate, $endDate): array
    {
        // Create a cache key based on the date range
        $cacheKey = "ride_metrics:{$startDate}:{$endDate}";

        // Try to get the metrics from cache
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($startDate, $endDate) {
            $rides = Ride::whereBetween('date', [$startDate, Carbon::parse($endDate)->addDay()])->get();

            return [
                $rides,
                [
                    'distance'      => $rides->sum('distance'),
                    'calories'      => $rides->sum('calories'),
                    'elevation'     => $rides->sum('elevation'),
                    'ride_count'    => $rides->count(),
                    'max_speed'     => $rides->max('max_speed'),
                    'average_speed' => number_format($rides->avg('average_speed'), 1),
                ],
                $startDate,
                $endDate,
            ];
        });
    }

    /**
     * Clear the cache for all ride metrics
     * This should be called when rides are created, updated, or deleted
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::flush();
    }
}
