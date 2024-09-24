<?php

namespace App\States;

use Thunk\Verbs\State;

class PlayerState extends State
{
    public string $name;

    public int $balance;

    public bool $setup;

    public array $hand;

    public int $score;

    // The best things in life are free, not cheap. - Lil Wayne
}
