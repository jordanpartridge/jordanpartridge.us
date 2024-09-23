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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SyncActivities extends Command
{
    protected $signature = 'sync';
    protected $description = 'Sync activities from Strava API';

    public function handle(): void
    {
        activity('sync')->log('started');
        try {
            $this->validateTokens();
            $this->syncActivities();
        } catch (Exception $e) {
            $this->error($e->getMessage());
            Log::error('Sync activities failed', ['exception' => $e]);
            activity('sync')->withProperties(['exception' => $e])->log('failed');
        }
    }

    private function validateTokens(): void
    {
        if (StravaToken::query()->count() === 0) {
            throw new Exception('No token found. Please add a token first.');
        }
    }

    private function syncActivities(): void
    {
        activity('sync')->log('syncing activities');
        StravaToken::query()->each(function ($token) {
            $activities = $this->getActivities($token)
                ->filter(fn ($activity) => $this->isNewRideActivity($activity));

            $activities->each(fn ($activity) => $this->processActivity($activity, $token));

            $this->logSyncResults($activities);
        });
    }

    private function getActivities(StravaToken $token): Collection
    {
        $strava = new Strava($token->access_token);
        $activities = collect();
        $page = 1;

        do {
            $currentPageActivities = $this->fetchActivitiesPage($strava, $page);
            $activities = $activities->concat($currentPageActivities);
            $page++;
        } while ($currentPageActivities->isNotEmpty());

        return $activities;
    }

    private function fetchActivitiesPage(Strava $strava, int $page): Collection
    {
        try {
            $response = $strava->send(new AthleteActivityRequest(['page' => $page, 'per_page' => 200]));
            if ($response->failed()) {
                Log::error('Error getting activities', ['response' => $response->json()]);
                return collect();
            }
            return collect($response->json());
        } catch (Exception $e) {
            Log::error('API request failed', ['exception' => $e->getMessage()]);
            return collect();
        }
    }

    private function isNewRideActivity(array $activity): bool
    {
        return !Ride::query()->where('external_id', $activity['external_id'])->exists()
            && $activity['type'] === 'Ride';
    }

    private function processActivity(array $activity, StravaToken $token): void
    {
        $activity['map_url'] = $this->getMap($activity['map']['id'], $activity['map']['summary_polyline']);
        $activity['calories'] = $this->getActivityCalories($activity['id'], $token);

        $this->createOrUpdateRide($activity);
    }

    private function getMap(string $mapId, string $polyline): ?string
    {
        $apiKey = config('services.google_maps.key');
        $encodedPolyline = urlencode($polyline);
        $url = "https://maps.googleapis.com/maps/api/staticmap?size=600x600&maptype=roadmap&path=enc:{$encodedPolyline}&key={$apiKey}";

        try {
            $response = Http::get($url);
            if ($response->successful()) {
                $filename = "rides/{$mapId}.png";
                if (Storage::disk('s3')->put($filename, $response->body())) {
                    return $filename;
                }
            }
        } catch (Exception $e) {
            Log::error('Error fetching map from Google', ['exception' => $e->getMessage()]);
        }

        Log::warning('Map fetch failed, returning null');
        return null;
    }

    private function getActivityCalories(string $activityId, StravaToken $token): ?float
    {
        $strava = new Strava($token->access_token);
        $response = $strava->send(new ActivityRequest($activityId));
        return $response->json()['calories'] ?? null;
    }

    private function createOrUpdateRide(array $activity): void
    {
        activity('sync')->withProperties($activity)->log('creating or updating ride');
        Ride::query()->updateOrCreate(
            ['external_id' => $activity['external_id']],
            [
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
            ]
        );
    }

    private function logSyncResults(Collection $activities): void
    {
        if ($activities->isNotEmpty()) {
            activity('sync')->withProperties($activities)->log('synced activities');
            Log::info('Synced activities count', ['count' => $activities->count()]);
            Notification::make()
                ->title('Ride Sync Completed')
                ->success()
                ->send();
        }
    }
}
