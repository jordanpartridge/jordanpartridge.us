<?php

namespace App\Domain\BlackJack\Events;

use App\Domain\BlackJack\DataTransferObjects\GameDto;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameInitializedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly GameDto $game
    ) {
    }

    public function broadcastOn(): array
    {
        return [];
    }

    public function broadcastAs(): string
    {
        return 'game.initialized';
    }

    public function toArray(): array
    {
        return [
            'game'      => $this->game->toArray(),
            'timestamp' => now()->toIso8601String(),
            'event'     => 'game_initialized',
        ];
    }
}
