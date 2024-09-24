<?php

namespace App\Events;

use App\Models\Game;
use App\States\GameState;
use Thunk\Verbs\Attributes\Autodiscovery\AppliesToState;
use Thunk\Verbs\Event;

#[AppliesToState(GameState::class)]
class GameStarted extends Event
{
    public function __construct(
        public ?int $game_id = null,
        public ?string $name = null,
        public ?string $deck = null,
    ) {
    }

    public function validate(GameState $game): void
    {
        $this->assert(! $game->started, 'The game has already started');
        $this->assert(! empty($this->name), 'The game must have a name');
        $this->assert(! empty($this->deck), 'The game must have a deck');
        $this->assert(Game::where('name', $this->name)->doesntExist(), 'The game name must be unique');
    }

    public function game(): GameState
    {
        return GameState::load($this->game_id);
    }

    public function applyToGame(GameState $game): void
    {
        $game->started = true;
        $game->started_at = now()->toImmutable();
        $game->player_ids = [];
        Game::create([
            'name'          => $this->name,
            'deck_slug'     => $this->deck,
            'game_state_id' => $game->id,
        ]);

    }
}
