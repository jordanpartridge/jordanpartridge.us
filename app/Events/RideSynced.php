<?php

namespace App\Events;

use App\Models\Ride;
use App\Models\User;
use App\Notifications\RideSynced as RideSyncedNotification;
use Carbon\Carbon;
use Thunk\Verbs\Event;

class RideSynced extends Event
{
    public array $data;

    public function handle(): void
    {
        $ride = Ride::query()->updateOrCreate(
            ['external_id' => $activity['external_id']],
            [
                 'date'          => Carbon::parse($activity['start_date_local']),
                 'name'          => $activity['name'],
                 'distance'      => $activity['distance'],
                 'polyline'      => $activity['map']['summary_polyline'],
                 'map_url'       => $activity['map_url'],
                 'max_speed'     => $activity['max_speed'],
                 'calories'      => $activity['calories'],
                 'elevation'     => $activity['total_elevation_gain'],
                 'average_speed' => $activity['average_speed'],
                 'moving_time'   => $activity['moving_time'],
                 'elapsed_time'  => $activity['elapsed_time'],
             ]
        );

        User::first()->notify(new RideSyncedNotification($ride));
        activity('bikes')
            ->event('synced')
            ->on($ride)
            ->withProperties(['ride' => $this->data])
            ->log('Ride synced');
    }
}
