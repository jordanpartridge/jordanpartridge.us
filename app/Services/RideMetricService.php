<?php

namespace App\Services;

use App\Models\Ride;
use Carbon\Carbon;

class RideMetricService
{
    public function calculateRideMetrics($startDate, $endDate): array
    {
        return [
            $rides = Ride::whereBetween('date', [$startDate, Carbon::parse($endDate)->addDay()])->get(), [
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
    }
}
