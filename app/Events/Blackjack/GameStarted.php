<?php

namespace App\Events\Blackjack;

use App\Models\Game;
use App\States\Blackjack\DealerState;
use App\States\Blackjack\GameState;
use Thunk\Verbs\Attributes\Autodiscovery\AppliesToState;
use Thunk\Verbs\Event;

#[AppliesToState(GameState::class)]
#[AppliesToState(DealerState::class)]
class GameStarted extends Event
{
    public function __construct(
        public ?int $game_id = null,
        public ?int $dealer_id = null,
        public ?string $name = null,
    ) {
    }

    public function validate(GameState $game): void
    {
        $this->assert(! $game->started, 'The game has already started');
        $this->assert(! empty($this->name), 'The game must have a name');
        $this->assert(Game::where('name', $this->name)->doesntExist(), 'The game name must be unique');
    }

    public function applyToGame(GameState $game): void
    {
        $game->started = true;
        $game->started_at = now()->toImmutable();
        $game->player_ids = [];
        $game->dealer_id = $this->dealer_id;

    }

    public function applyToDealer(DealerState $dealer): void
    {
        $dealer->game_id = $this->game_id;
    }

    public function apply(GameState $gameState): Game
    {
        return Game::create([
            'name'       => $this->name,
            'game_id'    => $this->game_id,
            'created_at' => $gameState->started_at,
        ]);
    }
}
