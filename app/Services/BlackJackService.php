<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Saloon\Exceptions\Request\RequestException;

class BlackJackService
{
    private array $deck;

    public function __construct(
        private CardService $cardService,
        private string $deckName = 'blackjack-deck'
    ) {
    }

    /**
     * Initialize a new game of BlackJack.
     *
     * @param  string  $name  The name of the game.
     * @param  array  $players  An array of player names.
     * @return Game The newly created game with associated players.
     *
     * @throws ValidationException If the game data is invalid.
     * @throws RuntimeException If deck initialization fails.
     */
    public function initializeGame(string $name, array $players): Game
    {
        $this->initializeDeck();
        $this->validateGameData($name);

        $game = $this->createGame($name);
        $this->createPlayers($game, $players);

        return $game->load('players');
    }

    /**
     * Initialize the deck for the game.
     *
     * @throws RuntimeException If deck initialization fails.
     */
    private function initializeDeck(): void
    {
        try {
            $this->deck = $this->cardService->initializeDeck($this->deckName);
        } catch (RequestException $e) {
            throw new RuntimeException('Failed to initialize deck: ' . $e->getMessage());
        }
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
        $validator = Validator::make(
            ['name' => $name, 'deck_slug' => $this->deck['slug']],
            [
                'name'      => ['required', 'string', 'max:255', 'unique:games'],
                'deck_slug' => ['required', 'string', 'max:255', 'unique:games'],
            ]
        );

        if ($validator->fails()) {
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

    /**
     * Create players for the game.
     *
     * @param  Game  $game  The game to associate players with.
     * @param  array  $players  An array of player names.
     */
    private function createPlayers(Game $game, array $players): void
    {
        Collection::make($players)->each(function ($playerName) use ($game) {
            $game->players()->updateOrCreate(
                ['name' => $playerName],
                [
                    'email'    => $playerName . '@example.com',
                    'password' => bcrypt(Str::uuid()),
                ]
            );
        });
    }
}
