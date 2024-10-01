<?php

namespace App\States\Blackjack;

use Thunk\Verbs\State;

class DealerState extends State
{
    public array $hand;

    public string $deck;

    public int $game_id;
}
