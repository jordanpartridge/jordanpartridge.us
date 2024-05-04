<?php

namespace App\Services;

use App\Models\Ride;

class RideMetricService
{
    public function calculateRideMetrics($startDate, $endDate): array
    {

        $rides = Ride::whereBetween('date', [$startDate, $endDate]);

        $metrics = [
            'distance'      => number_format($rides->sum('distance') * 0.000621371, 1) . ' miles',
            'calories'      => $rides->sum('calories'),
            'elevation'     => $rides->sum('elevation'),
            'max_speed'     => number_format($rides->max('max_speed') * 2.23694, 1),
            'average_speed' => number_format($rides->avg('average_speed') * 2.23694, 1),
            'ride_count'    => $rides->count(),
        ];

        return $metrics;
    }
}
