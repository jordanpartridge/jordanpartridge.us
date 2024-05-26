<?php

namespace App\Http\Handlers;

use App\Contracts\WebhookHandlerInterface;
use App\Models\Deck;
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
            '/start-game' => 'startGame',
        ];
    }

    /**
     * Handle the incoming webhook payload.
     *
     * @param array $payload
     */
    public function handle(array $payload): IlluminateResponse
    {
        $command = $payload['command'] ?? null;

        if ($command && array_key_exists($command, $this->commands)) {
            $methodName = $this->commands[$command];
            return $this->$methodName($payload); // Call the method based on the command
        }

        return response(['message' => 'Unknown command: ' . $command], 400);
    }

    /**
     * List all rides
     */
    private function listRides(array $payload): IlluminateResponse
    {
        $rides = Ride::all();
        $header = sprintf("%-50s %-50s", "Ride Name", "Ride Distance");

        // Collecting log messages, to be simplified
        $logMessages = $rides->map(function ($ride) use ($header) {
            return $header . "\n" . sprintf("%-50s %-50s", $ride->name, $ride->distance);
        })->implode("\n");

        Log::channel('slack')->info($logMessages);
        return response('Listing Rides please wait', 200);
    }

    /**
     * Sync rides
     */
    private function syncRides(array $payload): IlluminateResponse
    {
        Artisan::queue('sync'); // This example assumes you have a corresponding Artisan command

        return response(['text' => 'Sync process started, we will notify you once done!'], 200);
    }

    /*
     * Start a game
     */
    private function startGame(array $payload): IlluminateResponse
    {
        $deck = new Deck();
        $deck->shuffle();
        $playerCards = $deck->draw(2);
        $dealerCards = $deck->draw(2);
        Log::channel('slack')->info('Game started', ['player_cards' => $playerCards, 'dealer_cards' => $dealerCards]);
        $userId = $payload['user_id'];
        return response(['text' => "Dealing in:  <@$userId> with $900"], 200);

    }


    /**
     * Determine if the handler should handle the incoming payload.
     *
     * @param array $payload
     * @return bool
     */
    public function shouldHandle(array $payload): bool
    {
        return isset($payload['command']);
    }
}
