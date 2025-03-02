<?php

namespace App\Observers;

use App\Models\Ride;
use App\Models\User;
use App\Notifications\RideSynced;
use App\Services\RideMetricService;
use Exception;
use Illuminate\Support\Facades\Notification;

class RideObserver
{
    /**
     * The RideMetricService instance.
     */
    protected RideMetricService $rideMetricService;

    /**
     * Create a new observer instance.
     */
    public function __construct(RideMetricService $rideMetricService)
    {
        $this->rideMetricService = $rideMetricService;
    }

    /**
     * Handle the Ride "created" event.
     *
     * @throws Exception
     */
    public function created(Ride $ride): void
    {
        if (app()->environment('testing')) {
            return;
        }

        // Skip notification if no users exist in testing or any environment
        if (!User::first()) {
            return;
        }

        // Clear the cache when a new ride is created
        $this->rideMetricService->clearCache();

        Notification::route('slack', config('services.slack.webhook_url'))
            ->notify(new RideSynced($ride));
    }

    /**
     * Handle the Ride "updated" event.
     */
    public function updated(Ride $ride): void
    {
        // Clear the cache when a ride is updated
        $this->rideMetricService->clearCache();
    }

    /**
     * Handle the Ride "deleted" event.
     */
    public function deleted(Ride $ride): void
    {
        // Clear the cache when a ride is deleted
        $this->rideMetricService->clearCache();
    }

    /**
     * Handle the Ride "restored" event.
     */
    public function restored(Ride $ride): void
    {
        // Clear the cache when a ride is restored
        $this->rideMetricService->clearCache();
    }

    /**
     * Handle the Ride "force deleted" event.
     */
    public function forceDeleted(Ride $ride): void
    {
        // Clear the cache when a ride is force deleted
        $this->rideMetricService->clearCache();
    }
}
