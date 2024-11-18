<?php

namespace App\Console\Commands;

use App\Events\RideSynced;
use App\Models\Ride;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use JordanPartridge\StravaClient\Facades\StravaClient;
use JordanPartridge\StravaClient\Models\StravaToken;

class SyncActivities extends Command
{
    protected $signature = 'sync';

    protected $description = 'Sync activities from Strava API';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $this->validateTokens()
            ->syncActivities();

    }

    /**
     * @throws Exception
     */
    private function validateTokens(): self
    {
        if (StravaToken::query()->count() === 0) {
            throw new Exception('No token found. Please add a token first.');
        }

        return $this;
    }

    private function syncActivities(): void
    {
        StravaToken::query()->each(function ($token) {
            $this->info('Syncing activities for token: ' . $token->id);
            $activities = $this->getActivities($token);
            $activities->each(fn ($activity) => $this->processActivity($activity, $token));

        });
    }

    private function getActivities(StravaToken $token): Collection
    {
        StravaClient::setToken($token->access_token, $token->refresh_token);

        return $this->fetchActivitiesPage(1);
    }

    private function fetchActivitiesPage(int $page): Collection
    {
        return collect(StravaClient::activityForAthlete($page, 200));
    }

    private function isNewRideActivity(array $activity): bool
    {
        return ! Ride::query()->where('external_id', $activity['external_id'])->exists()
            && $activity['type'] === 'Ride';
    }

    private function processActivity(array $activity, StravaToken $token): void
    {
        $this->info('Processing activity: ' . $activity['name']);
        $activity['map_url'] = $this->getMap($activity['map']['id'], $activity['map']['summary_polyline']);
        $activity['calories'] = $this->getActivityCalories($activity['id'], $token);

        RideSynced::fire(ride: $activity);
    }

    /**
     * @todo make strava-client or another package to handle this
     */
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
            report($e);
        }

        return null;
    }

    /**
     * Get calories from getActivity endpoint.
     */
    private function getActivityCalories(string $activityId, StravaToken $token): ?float
    {
        StravaClient::setToken($token->access_token, $token->refresh_token);
        $activity = StravaClient::getActivity($activityId);

        return $activity['calories'] ?? null;
    }
}
