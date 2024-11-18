<?php

namespace App\Events;

use App\Models\Ride;
use App\Models\User;
use App\Notifications\RideSynced as RideSyncedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Thunk\Verbs\Event;

class RideSynced extends Event
{
    public array $ride;

    public function handle(): void
    {
        $ride = Ride::query()->updateOrCreate(
            ['external_id' => $this->ride['external_id']],
            [
                'date'          => Carbon::parse($this->ride['start_date_local']),
                'name'          => $this->ride['name'],
                'distance'      => $this->ride['distance'],
                'polyline'      => $this->ride['map']['summary_polyline'],
                'map_url'       => $this->ride['map_url'],
                'max_speed'     => $this->ride['max_speed'],
                'calories'      => $this->ride['calories'],
                'elevation'     => $this->ride['total_elevation_gain'],
                'average_speed' => $this->ride['average_speed'],
                'moving_time'   => $this->ride['moving_time'],
                'elapsed_time'  => $this->ride['elapsed_time'],
            ]
        );

        // Get the user who should receive the notification
        $user = User::first();

        if (! $user) {
            Log::error('No users found to notify about synced ride');

            return;
        }

        $user->notify(new RideSyncedNotification($ride));

        Log::stack(['slack'])->info('Ride synced', [
            'ride'              => $ride,
            'notification_sent' => true,
            'user_id'           => $user->id,
        ]);

        activity('bikes')
            ->event('synced')
            ->on($ride)
            ->withProperties(['name' => $this->ride['name'], 'distance' => $this->ride['distance']])
            ->log('Ride synced');
    }
}
