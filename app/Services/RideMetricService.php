<?php

namespace App\Services;

use App\Models\Ride;

class RideMetricService
{
    public function calculateRideMetrics($startDate, $endDate): array
    {
        $metrics = Ride::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('
                ROUND(SUM(distance)) as distance,
                SUM(calories) as total_calories,
                SUM(elevation) as total_elevation,
                MAX(max_speed) as max_speed,
                AVG(average_speed) as average_speed,
                COUNT(*) as ride_count
            ')
            ->first()
            ->toArray();

        $metrics = [
            'distance'      => $metrics['distance'],
            'calories'      => $metrics['total_calories'],
            'elevation'     => $metrics['total_elevation'],
            'max_speed'     => number_format($metrics['max_speed'], 1),
            'average_speed' => number_format($metrics['average_speed'], 1),
            'ride_count'    => $metrics['ride_count'],
        ];

        return $metrics;
    }
}
