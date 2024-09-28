<?php

namespace App\States;

use Thunk\Verbs\State;

class DealerState extends State
{
    public array $hand;

    public string $deck;
}
