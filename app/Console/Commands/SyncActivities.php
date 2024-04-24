<?php

namespace App\Console\Commands;

use App\Http\Integrations\Strava\Requests\ActivityRequest;
use App\Http\Integrations\Strava\Requests\AthleteActivityRequest;
use App\Http\Integrations\Strava\Strava;
use App\Models\Ride;
use App\Models\StravaToken;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Illuminate\Console\Command;

use function Laravel\Prompts\note;
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
        if (StravaToken::query()->count() === 0) {
            info('No token found. Please add a token first.');
            return;
        }
        StravaToken::query()->each(function ($token) {
            $this->info('Syncing activities for token: ' . $token->access_token);
            $this->info('Expires at: ' . $token->expires_at);

            $response = $this->getActivities($token);

            $activities = collect($response->json());
            $activities = $activities->map(function ($activity) use ($token) {
                $this->info('Activity: ' . $activity['name']);
                $strava = new Strava($token->access_token);
                $moreDataResponse = $strava->send(new ActivityRequest($activity['id']));
                $activity['calories'] = $moreDataResponse->json()['calories'];
                return $activity;
            });

            info('get activities response: ' . $response->status());
            info('get activities response: ' . count($response->json()));
            $this->storeResults($activities);
            $this->displayRides($activities);

            $response->onError(function ($response) use ($token) {
                $response->json();
                table(
                    ['status', 'message', 'token', 'field', 'Code'],
                    [
                        [
                            $response->status(),
                            $response->json()['message'],
                            $token->access_token,
                            $response->json()['errors'][0]['field'],
                            $response->json()['errors'][0]['code'],
                        ],
                    ],
                );

                if ($response->status() === 401) {
                    $this->info('Refreshing token: ' . $token->access_token);
                    $token = $this->refreshToken($token);
                    $this->info('Token refreshed :' . $token->access_token);
                    $response = $this->getActivities($token);
                    $this->storeResults($response);
                }
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

    private function refreshToken(StravaToken $token): StravaToken
    {
        $this->info('Refreshing token');

        $strava = new Strava();
        $response = $strava->refreshToken($token->refresh_token);
        $this->info('Response: ' . $response->status());
        if ($response->status() !== 200) {
            $this->error('Token not refreshed');
            return $token;
        }

        $token->update([
            'access_token'  => $response->json()['access_token'],
            'expires_at'    => now()->addSeconds($response->json()['expires_in']),
            'refresh_token' => $response->json()['refresh_token'],
        ]) ? $this->info('Token refreshed') : $this->error('Token not refreshed');

        return $token->fresh();
    }

    private function displayFunctionSummary(string $functionName, array $parameters): void
    {
        note($functionName . ' function called with the following parameters:');
        collect($parameters)->each(function ($value, $key) {
            $this->info($key . ':');
            $value->promptTable();
        });
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


    private function storeResults(Collection $activities): void
    {
        $activities->where('type', 'Ride')->each(function ($activity) {
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
        });
        info('Activities synced');
    }
}
