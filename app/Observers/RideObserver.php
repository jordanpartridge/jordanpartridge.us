<?php

namespace App\Observers;

use App\Models\Ride;
use App\Models\User;
use App\Notifications\RideSynced;
use Exception;
use Illuminate\Support\Facades\Notification;

class RideObserver
{
    /**
     * Handle the Ride "created" event.
     *
     * @throws Exception
     */
    public function created(Ride $ride): void
    {
        if (! User::first()) {
            throw new Exception('No users to notify or rides.');
        }

        Notification::route('slack', config('services.slack.webhook_url'))
            ->notify(new RideSynced($ride));
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
