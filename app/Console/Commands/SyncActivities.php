<?php

namespace App\Console\Commands;

use App\Http\Integrations\Strava\Requests\ActivityRequest;
use App\Http\Integrations\Strava\Requests\AthleteActivityRequest;
use App\Http\Integrations\Strava\Strava;
use App\Models\Ride;
use App\Models\StravaToken;
use Carbon\Carbon;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;

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
            $activities = $activities->filter(function ($activity) use ($token) {
                $existingRide = Ride::query()->where('external_id', $activity['external_id'])->first();
                if ($existingRide || $activity['type'] !== 'Ride') {
                    return false;
                }

                //save map to s3
                $map = $this->getMap(mapId: $activity['map']['id'], polyline: $activity['map']['summary_polyline']);
                $activity['map_url'] = $map;


                $strava = new Strava($token->access_token);
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
                    'map_url'       => $activity['map_url'],
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
                Notification::make()
                    ->title('Ride Sync Completed')
                    ->success()
                    ->send();
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
    }

    /**
     * @param string $mapId
     * @param        $polyline
     * @return string|null
     */
    private function getMap(string $mapId, $polyline): ?string
    {
        $apiKey = config('services.google.maps.api_key');
        $encodedPolyline = urlencode($polyline);
        $url = "https://maps.googleapis.com/maps/api/staticmap?size=600x600&maptype=roadmap&path=enc:{$encodedPolyline}&key={$apiKey}";
        dd($url);
        Log::info('Attempting to fetch map', ['url' => $url]);

        try {
            $response = Http::get($url);

            Log::info('Google Maps API response', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 100), // Log first 100 characters of body
            ]);

            if ($response->successful()) {
                $imageData = $response->body();
                $filename = "rides/{$mapId}.png";

                $putResult = Storage::disk('s3')->put($filename, $imageData);
                Log::info('S3 put result', ['result' => $putResult]);

                if ($putResult) {
                    $url = Storage::disk('s3')->url($filename);
                    Log::info('S3 URL generated', ['url' => $url]);
                    return $url;
                }
            }
        } catch (Exception $e) {
            Log::error('Error fetching map from Google', ['exception' => $e->getMessage()]);
        }

        Log::warning('Map fetch failed, returning null');
        return null;
    }
}
