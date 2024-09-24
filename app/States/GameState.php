<?php

namespace App\States;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Thunk\Verbs\State;

class GameState extends State
{
    public bool $started = false;

    public ?string $name = null;

    public int $active_player_id;

    public array $player_ids;

    public CarbonImmutable $started_at;

    public function players(): Collection
    {
        return collect($this->player_ids)->map(fn (int $id) => PlayerState::load($id));
    }

    public function activePlayer(): ?PlayerState
    {
        return $this->active_player_id ? PlayerState::load($this->active_player_id) : null;
    }
}
