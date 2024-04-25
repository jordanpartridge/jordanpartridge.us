<?php

namespace App\Console\Commands;

use App\Http\Integrations\Strava\Requests\ActivityRequest;
use App\Http\Integrations\Strava\Requests\AthleteActivityRequest;
use App\Http\Integrations\Strava\Strava;
use App\Models\Ride;
use App\Models\StravaToken;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Saloon\Http\Response;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;

use function Laravel\Prompts\info;

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
    protected $description = 'Sync activities from strava api';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Artisan::call('strava:token-refresh');
        if (StravaToken::query()->count() === 0) {
            info('No token found. Please add a token first.');
            return;
        }
        StravaToken::query()->each(function ($token) {

            $response = $this->getActivities($token);

            $activities = collect($response->json());
            $activities = $activities->filter(function ($activity) use ($token) {
                $existingRide = Ride::query()->where('external_id', $activity['external_id'])->first();
                if ($existingRide || $activity['type'] !== 'Ride') {
                    return false;
                }

                $strava = new Strava($token->access_token);
                $moreDataResponse = $strava->send(new ActivityRequest($activity['id']));
                $activity['calories'] = $moreDataResponse->json()['calories'];
                Ride::query()->updateOrCreate([
                    'external_id' => $activity['external_id'],
                ], [
                    'date'          => Carbon::parse($activity['start_date_local']),
                    'name'          => $activity['name'],
                    'distance'      => $activity['distance'],
                    'polyline'      => $activity['map']['summary_polyline'],
                    'max_speed'     => $activity['max_speed'],
                    'calories'      => $activity['calories'],
                    'elevation'     => $activity['total_elevation_gain'],
                    'average_speed' => $activity['average_speed'],
                    'moving_time'   => $activity['moving_time'],
                    'elapsed_time'  => $activity['elapsed_time'],
                ]);
                return $activity;
            });

            $this->info('Rides to Sync: ' . count($activities));



            $response->onError(function ($response) use ($token) {
                Log::error('Error syncing activities', [
                    'token'    => $token->access_token,
                    'response' => $response->json(),
                ]);
            });
        });
    }

    /**
     * Get activities from Strava
     */
    private function getActivities(StravaToken $token): Response
    {
        $strava = new Strava($token->access_token);
        $activities = new AthleteActivityRequest();

        return $strava->send($activities);
    }

    private function displayRides(Collection $rides): void
    {
        $rideData = $rides->map(function ($ride) {

            return [
                'date'          => $ride['start_date_local'],
                'name'          => $ride['name'],
                'distance'      => $ride['distance'],
                'max_speed'     => $ride['max_speed'],
                'calories'      => $ride['calories'],
                'elevation'     => $ride['total_elevation_gain'],
                'average_speed' => $ride['average_speed'],
                'moving_time'   => $ride['moving_time'],
                'elapsed_time'  => $ride['elapsed_time'],
            ];
        })->toArray();

        table(
            ['date', 'name', 'distance', 'max_speed', 'elevation', 'calories', 'average_speed', 'moving_time', 'elapsed_time'],
            $rideData
        );
    }

}
