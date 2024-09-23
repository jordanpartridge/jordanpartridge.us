<?php

namespace App\Http\Controllers\Cards;

use App\Services\CardService;

class DeckInitializeController
{
    /**
     */
    public function __invoke(CardService $cardService, string $deckName): array
    {
        return $cardService->initializeDeck($deckName);
    }
}
