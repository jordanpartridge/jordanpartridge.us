<?php

namespace App\Observers;

use App\Models\Ride;
use App\Models\User;
use App\Notifications\RideSynced;

class RideObserver
{
    /**
     * Handle the Ride "created" event.
     */
    public function created(Ride $ride): void
    {
        if(config('app.env') === 'testing') {
            return;
        }

        User::all()->each(function ($user) use ($ride) {
            $user->notify(new RideSynced($ride));
        });
    }

    /**
     * Handle the Ride "updated" event.
     */
    public function updated(Ride $ride): void
    {
        //
    }

    /**
     * Handle the Ride "deleted" event.
     */
    public function deleted(Ride $ride): void
    {
        //
    }

    /**
     * Handle the Ride "restored" event.
     */
    public function restored(Ride $ride): void
    {
        //
    }

    /**
     * Handle the Ride "force deleted" event.
     */
    public function forceDeleted(Ride $ride): void
    {
        //
    }
}
