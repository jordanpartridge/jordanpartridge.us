<?php

namespace App\States;

use Carbon\CarbonImmutable;
use Thunk\Verbs\State;

class GameState extends State
{
    public bool $started = false;

    public ?string $name = null;

    public int $active_player_id;

    public array $player_ids;

    public CarbonImmutable $started_at;
}
