<?php

namespace App\Events\Blackjack;

use App\States\Blackjack\GameState;
use App\States\Blackjack\PlayerState;
use Thunk\Verbs\Event;

class Deal extends Event
{
    public function __construct(
        public ?int $game_id = null,
    ) {
    }

    public function applyToPlayers(PlayerState $player, GameState $gameState): void
    {
        $players = $gameState->fresh()->players();
    }

    public function handle()
    {
        // I'm on a paper chase until my toes bleed - Lil Wayne
    }
}
