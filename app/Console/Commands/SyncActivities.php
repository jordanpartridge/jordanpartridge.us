<?php

namespace App\Console\Commands;

use App\Events\CommandFailed;
use App\Events\RideSynced;
use App\Http\Integrations\Strava\Requests\ActivityRequest;
use App\Http\Integrations\Strava\Requests\AthleteActivityRequest;
use App\Http\Integrations\Strava\Strava;
use App\Models\Ride;
use App\Models\StravaToken;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class SyncActivities extends Command
{
    protected $signature = 'sync';

    protected $description = 'Sync activities from Strava API';

    public function handle(): void
    {
        try {
            $this->validateTokens();
            $this->syncActivities();
        } catch (Exception $e) {
            $this->error($e->getMessage());
            CommandFailed::fire(command: $this->signature, message: $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function validateTokens(): void
    {
        if (StravaToken::query()->count() === 0) {
            throw new Exception('No token found. Please add a token first.');
        }
    }

    private function syncActivities(): void
    {
        StravaToken::query()->each(function ($token) {
            $activities = $this->getActivities($token)
                ->filter(fn ($activity) => $this->isNewRideActivity($activity));

            $activities->each(fn ($activity) => $this->processActivity($activity, $token));

        });
    }

    private function getActivities(StravaToken $token): Collection
    {
        $strava = new Strava($token->access_token);
        $activities = collect();
        $page = 1;

        do {
            $currentPageActivities = $this->fetchActivitiesPage($strava, $page)
                ->filter(fn ($activity) => $activity['type'] === 'Ride');

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
                report($response->toException());

                return collect();
            }

            return collect($response->json());
        } catch (Exception $e) {
            Log::error('API request failed', ['exception' => $e->getMessage()]);
            report($e);

            return collect();
        }
    }

    private function isNewRideActivity(array $activity): bool
    {
        return ! Ride::query()->where('external_id', $activity['external_id'])->exists()
            && $activity['type'] === 'Ride';
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    private function processActivity(array $activity, StravaToken $token): void
    {
        $activity['map_url'] = $this->getMap($activity['map']['id'], $activity['map']['summary_polyline']);
        $activity['calories'] = $this->getActivityCalories($activity['id'], $token);

        RideSynced::fire(data: $activity);
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
            report($e);
        }

        return null;
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    private function getActivityCalories(string $activityId, StravaToken $token): ?float
    {
        $strava = new Strava($token->access_token);
        $response = $strava->send(new ActivityRequest($activityId));
        if ($response->failed()) {
            report(new Exception('Error getting activity calories'));
        }

        return $response->json()['calories'] ?? null;
    }
}
