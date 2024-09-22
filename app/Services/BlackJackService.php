<?php

namespace App\Services;

use App\Models\Game;
use Illuminate\Support\Collection;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class BlackJackService
{
    private array $deck;
    public function __construct(private CardService $cardService, private string $deckName = 'blackjack-deck')
    {
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function initializeGame(string $name, array $players): array
    {
        $this->deck = $this->cardService->initializeDeck($name);
        $game = Game::create(['name' => $name, 'deck' => $this->deck['slug']]);
        Collection::make($players)->each(function ($player) use ($game) {
            $game->players()->create(['name' => $player]);
        });
        return $game->load('players');

    }
}
