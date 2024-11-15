<?php

namespace App\Http\Handlers;

use App\Contracts\WebhookHandlerInterface;
use App\Models\Ride;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SlackCommandHandler implements WebhookHandlerInterface
{
    private $commands;

    public function __construct()
    {
        // Store references to the method names as strings
        $this->commands = [
            '/list-rides' => 'listRides',
            '/sync-rides' => 'syncRides',
        ];
    }

    /**
     * Handle the incoming webhook payload.
     */
    public function handle(array $payload): IlluminateResponse
    {
        $command = $payload['command'] ?? null;

        if ($command && array_key_exists($command, $this->commands)) {
            $methodName = $this->commands[$command];

            return $this->$methodName(); // Call the method based on the command
        }

        return response(['message' => 'Unknown command: ' . $command], 400);
    }

    /**
     * Determine if the handler should handle the incoming payload.
     */
    public function shouldHandle(array $payload): bool
    {
        return isset($payload['command']);
    }

    /**
     * List all rides
     */
    private function listRides(): IlluminateResponse
    {
        $rides = Ride::all();
        $header = sprintf('%-50s %-50s', 'Ride Name', 'Ride Distance');

        // Collecting log messages, to be simplified
        $logMessages = $rides->map(function ($ride) use ($header) {
            return $header . "\n" . sprintf('%-50s %-50s', $ride->name, $ride->distance);
        })->implode("\n");

        Log::channel('slack')->info($logMessages);

        return response('Listing Rides please wait', 200);
    }

    /**
     * Sync rides
     */
    private function syncRides(): IlluminateResponse
    {
        Artisan::queue('sync');

        return response(['text' => 'Syncing rides please standby'], 200);
    }
}
