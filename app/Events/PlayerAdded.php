<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\UnknownCurrencyException;
use Thunk\Verbs\Attributes\Autodiscovery\AppliesToState;
use Thunk\Verbs\Event;

#[AppliesToState(GameState::class)]
#[AppliesToState(PlayerState::class)]
class PlayerAdded extends Event
{
    public function __construct(
        public ?int $game_id = null,
        public ?int $player_id = null,
        public ?string $name = null,
    ) {
    }

    public function applyToGame(GameState $game): void
    {
        $game->player_ids[] = $this->player_id;
        if (count($game->player_ids) === 1) {
            $game->active_player_id = $this->player_id;
        }
    }

    /**
     * @throws UnknownCurrencyException
     * @throws RoundingNecessaryException
     * @throws NumberFormatException
     */
    public function applyToPlayer(PlayerState $player): void
    {
        $player->name = $this->name;
        $player->balance = 500;
        $player->setup = true;
        $player->hand = [];
    }
}
