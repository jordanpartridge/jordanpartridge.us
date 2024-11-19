<?php

namespace App\Jobs;

use App\Events\RideSynced;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use JordanPartridge\StravaClient\Facades\StravaClient;
use JordanPartridge\StravaClient\Models\StravaToken;

class SyncActivitiesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $this->validateTokens()
            ->syncActivities();

    }

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
            $activities = $this->getActivities($token);
            $activities->each(fn ($activity) => $this->processActivity($activity, $token));
        });
    }

    private function processActivity(array $activity, StravaToken $token): void
    {
        $activity['map_url'] = $this->getMap($activity['map']['id'], $activity['map']['summary_polyline']);
        $activity['calories'] = $this->getActivityCalories($activity['id'], $token);

        RideSynced::fire(ride: $activity);
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

    /**
     * Get calories from getActivity endpoint.
     */
    private function getActivityCalories(string $activityId, StravaToken $token): ?float
    {
        StravaClient::setToken($token->access_token, $token->refresh_token);
        $activity = StravaClient::getActivity($activityId);

        return $activity['calories'] ?? null;
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
}
