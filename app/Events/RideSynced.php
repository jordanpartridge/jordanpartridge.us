<?php

namespace App\Events;

use App\Models\Ride;
use App\Models\User;
use App\Notifications\RideSynced as RideSyncedNotification;
use Thunk\Verbs\Event;

class RideSynced extends Event
{
    public Ride $ride;

    public function handle(): void
    {
        User::first()->notify(new RideSyncedNotification($this->ride));
        activity('bikes')
            ->event('synced')
            ->on($this->ride)
            ->withProperties(['ride' => $this->ride])
            ->log('Ride synced');
    }
}
