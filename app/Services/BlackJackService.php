<?php

namespace App\Services;

use App\Events\Blackjack\Deal;
use App\Events\Blackjack\GameStarted;
use App\Events\Blackjack\PlayerAdded;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use JsonException;
use RuntimeException;
use stdClass;

class BlackJackService
{
    public function __construct(
        private readonly CardService $cardService,
        private object $game = new stdClass(),
    ) {
    }

    /**
     * Initialize a new game of BlackJack.
     *
     * @param  string  $name  The name of the game.
     *
     * @throws JsonException
     */
    public function initializeGame(string $name): void
    {
        $this->game->name = $name;

        activity('blackjack')->withProperties(['game' => $this->game])->log('Game started');

        $event = GameStarted::fire(name: $name);

        $this->game->deck = $this->initializeDeck($name);

        $this->game->id = $event->game_id;
    }

    public function deal(): void
    {
        Deal::fire($this->game->id);
    }

    public function addPlayers(array $playerNames): void
    {
        collect($playerNames)->each(function ($playerName) {
            $event = PlayerAdded::fire(name: $playerName, game_id: $this->game->id);
            $player = new stdClass();
            $player->id = $event->player_id;
            $player->name = $playerName;
            $this->game->players[] = $player;
        });
        activity('blackjack')->withProperties(['game' => $this->game])->log('Players added');
    }

    /**
     * Initialize the deck for the game.
     *
     * @throws RuntimeException|JsonException If deck initialization fails.
     */
    private function initializeDeck(string $name): array
    {
        $response = $this->cardService->initializeDeck($name);

        return $response->successful() ? $response->json() : ['error' => $response->toException()->getMessage()];
    }

    /**
     * Validate the game data.
     *
     * @param  string  $name  The name of the game.
     *
     * @throws ValidationException If validation fails.
     */
    private function validateGameData(string $name): void
    {
        activity('blackjack-service')
            ->withProperties(['name' => $name, 'deck_slug' => $this->deck['slug'] ?? null])
            ->log('validating game data');

        $validator = Validator::make(
            [
                'name'      => $name,
                'deck_slug' => $this->deck['slug'] ?? null,
            ],
            [
                'name'      => ['required', 'string', 'max:255', 'unique:games'],
                'deck_slug' => ['required', 'string', 'max:255', 'unique:games'],
            ]
        );

        if ($validator->fails()) {
            activity('blackjack-service')
                ->event('validation-failed')
                ->withProperties($validator->errors()->toArray())
                ->log('validation failed');
            throw new ValidationException($validator);
        }
    }

    /**
     * Create a new game.
     *
     * @param  string  $name  The name of the game.
     * @return Game The newly created game.
     */
    private function createGame(string $name): Game
    {
        return Game::create([
            'name'      => $name,
            'deck_slug' => $this->deck['slug'],
        ]);
    }
}
