<?php

namespace App\States;

use Brick\Money\Money;
use Thunk\Verbs\State;

class PlayerState extends State
{
    public string $name;

    public Money $balance;

    public bool $setup;

    public array $hand;

    public int $score;

    public ?int $game_id = null;

    public function game(): ?GameState
    {
        return $this->game_id ? GameState::load($this->game_id) : null;
    }
}
