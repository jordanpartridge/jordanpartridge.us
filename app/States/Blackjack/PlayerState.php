<?php

namespace App\States\Blackjack;

use Thunk\Verbs\State;

class PlayerState extends State
{
    public string $name;

    public int $balance;

    public bool $setup;

    public array $hand;

    public int $score;

    public ?int $game_id = null;

    public function game(): ?GameState
    {
        return $this->game_id ? GameState::load($this->game_id) : null;
    }
}
