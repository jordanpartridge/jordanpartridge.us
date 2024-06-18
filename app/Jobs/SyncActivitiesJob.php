<?php

namespace App\Jobs;

use App\Models\StravaToken;
use App\Models\Ride;
use App\Http\Integrations\Strava\Requests\ActivityRequest;
use App\Http\Integrations\Strava\Requests\AthleteActivityRequest;
use App\Http\Integrations\Strava\Strava;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;

class SyncActivitiesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        Artisan::call('strava:token-refresh');
        if (StravaToken::query()->count() === 0) {
            Log::channel('slack')->info('No token found. Please add a token first.');
            return;
        }

        StravaToken::query()->each(function ($token) {
            $response = $this->getActivities($token);
            $activities = $response->filter(function ($activity) use ($token) {
                $existingRide = Ride::query()->where('external_id', $activity['external_id'])->first();
                if ($existingRide || $activity['type'] !== 'Ride') {
                    return false;
                }

                $strava = new Strava($token->access_token);
                $moreDataResponse = $strava->send(new ActivityRequest($activity['id']));
                $activity['calories'] = $moreDataResponse->json()['calories'];
                Log::channel('slack')->info('Ride added', ['ride' => $activity['name']]);

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
                    Log::error('Error getting activities', ['response' => $response->json()]);
                    break;
                }

                $currentPageActivities = collect($response->json());
                $activities = $activities->concat($currentPageActivities);
                $page++;
                sleep(1);
            } catch (Exception $e) {
                Log::error('API request failed', ['exception' => $e->getMessage()]);
                break;
            }
        } while ($currentPageActivities->isNotEmpty());

        return $activities;
    }
}
