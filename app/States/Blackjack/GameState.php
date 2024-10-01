<?php

namespace App\States\Blackjack;

use App\Models\Game;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Thunk\Verbs\State;

class GameState extends State
{
    public bool $started = false;

    public ?string $name = null;

    public ?int $dealer_id = null;

    public int $active_player_id;

    public array $player_ids = [];

    public CarbonImmutable $started_at;

    public function validate(): void
    {
        $this->assert(! $this->started, 'The game has already started');
        $this->assert(! empty($this->name), 'The game must have a name');
        $this->assert(! empty($this->deck_slug), 'The game must have a deck');
        $this->assert(Game::where('name', $this->name)->doesntExist(), 'The game name must be unique');
        $this->assert(Game::where('deck_slug', $this->deck_slug)->doesntExist(), 'The game deck must be unique');
        $this->assert(Str::length($this->name) < 256, 'The game name must not exceed 255 characters');
        $this->assert(Str::length($this->deck_slug) < 256, 'The game deck must not exceed 255 characters');

    }

    public function players(): Collection
    {
        return collect($this->player_ids)->map(fn (int $id) => PlayerState::load($id));
    }

    public function activePlayer(): ?PlayerState
    {
        return $this->active_player_id ? PlayerState::load($this->active_player_id) : null;
    }

    public function dealer(): ?DealerState
    {
        return DealerState::load($this->dealer_id);
    }

}
