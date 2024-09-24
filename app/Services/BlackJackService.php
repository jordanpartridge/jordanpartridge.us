<?php

namespace App\Services;

use App\Events\GameStarted;
use App\Models\Game;
use App\States\GameState;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use JsonException;
use RuntimeException;

readonly class BlackJackService
{
    public GameState $game;

    public function __construct(
        private CardService $cardService,
    ) {
    }

    /**
     * Initialize a new game of BlackJack.
     *
     * @param  string  $name  The name of the game.
     *
     * @throws JsonException
     */
    public function initializeGame(string $name): GameState
    {
        $deck = $this->initializeDeck($name);
        if (! isset($deck['slug'])) {
            throw new RuntimeException('Failed to create deck', $deck['error']);
        }

        return $this->game = GameStarted::fire(name: $name, deck: $deck['slug'])->game();
    }

    public function deal(Game $game): array
    {
        $initialHands = [];
        $players = $game->players()->get()->pluck('name')->push('dealer');

        $deck = $game->getAttribute('deck_slug');
        $players->each(function ($playerName) use (&$initialHands, $deck) {
            $cards = $this->cardService->drawCard($deck, 2);
            if ($cards->successful()) {
                $initialHands[$playerName] = $cards->json();
            } else {
                $initialHands[$playerName]['error'] = $cards->toException()->getMessage();
            }
        });

        activity('blackjack-service')
            ->withProperties($initialHands)
            ->event('deal')
            ->log('dealing cards');

        return $initialHands;
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
