<?php

namespace App\Console\Commands;

use App\Http\Integrations\Strava\Requests\ActivityRequest;
use App\Http\Integrations\Strava\Requests\AthleteActivityRequest;
use App\Http\Integrations\Strava\Strava;
use App\Models\Ride;
use App\Models\StravaToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

use function Laravel\Prompts\info;

class SyncActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync activities from strava api';

    protected StravaToken $token;

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        Artisan::call('strava:token-refresh');
        if (StravaToken::query()->count() === 0) {
            info('No token found. Please add a token first.');
            Log::channel('slack')->info('No token found. Please add a token first.');
            return;
        }

        StravaToken::query()->each(function ($token) {
            $response = $this->getActivities($token);
            $activities = $response;
            $this->token = $token;
            $activities = $activities->filter([$this, 'filterActivities']);


            if ($activities->isNotEmpty()) {
                Log::info('Synced activities count', ['count' => $activities->count()]);
            }
        });
    }

    /**
     * Get activities from Strava
     */
    private function getActivities(StravaToken $token): Collection
    {
        $strava = new Strava($token->access_token);
        $activities = collect();
        $page = 1;

        do {
            try {
                $response = $strava->send(new AthleteActivityRequest(['page' => $page, 'per_page' => 200]));
                if ($response->failed()) {
                    Log::error('Error getting activities', [
                        'response' => $response->json(),
                    ]);
                    break;
                }

                $currentPageActivities = collect($response->json());
                $activities = $activities->concat($currentPageActivities);
                $page++;
                sleep(1);
            } catch (\Exception $e) {
                Log::error('API request failed', ['exception' => $e->getMessage()]);
                break;
            }
        } while ($currentPageActivities->isNotEmpty());

        return $activities;
    }

    /**
     * Filter activities.
     *
     * @param mixed       $activity
     * @param mixed       $key
     * @param StravaToken $token
     * @throws \JsonException
     * @throws FatalRequestException
     * @throws RequestException
     * @return bool
     */
    public function filterActivities(array $activity, string $key): bool
    {
        $existingRide = Ride::query()->where('external_id', $activity['external_id'])->first();
        if ($existingRide || $activity['type'] !== 'Ride') {
            return false;
        }

        $strava = new Strava($this->token->access_token);
        $moreDataResponse = $strava->send(new ActivityRequest($activity['id']));
        $activity['calories'] = $moreDataResponse->json()['calories'];
        Log::channel('slack')->info('Ride added', ['ride' => $activity['name']]);

        // this already sends a slack notification
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

        return true;
    }

}
