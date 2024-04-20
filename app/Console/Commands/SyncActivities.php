<?php

namespace App\Console\Commands;

use App\Http\Integrations\Strava\Requests\ActivitiesRequest;
use App\Http\Integrations\Strava\Strava;
use App\Models\Ride;
use App\Models\StravaToken;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:activities-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        StravaToken::query()->each(function ($token) {
            $strava = new Strava($token->access_token);

            $activities = new ActivitiesRequest();

            $response = $strava->send($activities);

            collect($response->json())->where('type', 'Ride')->each(function ($activity) {
                Ride::query()->updateOrCreate([
                    'external_id' => $activity['external_id'],
                ], [
                    'date'          => Carbon::parse($activity['start_date_local']),
                    'name'          => $activity['name'],
                    'distance'      => $activity['distance'],
                    'max_speed'     => $activity['max_speed'],
                    'average_speed' => $activity['average_speed'],
                ]);
            });
        });
    }
}
