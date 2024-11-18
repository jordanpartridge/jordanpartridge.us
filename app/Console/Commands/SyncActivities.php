<?php

namespace App\Console\Commands;

use App\Jobs\SyncActivitiesJob;
use Exception;
use Illuminate\Console\Command;

class SyncActivities extends Command
{
    protected $signature = 'sync';

    protected $description = 'Sync activities from Strava API';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        SyncActivitiesJob::dispatch();
    }
}
