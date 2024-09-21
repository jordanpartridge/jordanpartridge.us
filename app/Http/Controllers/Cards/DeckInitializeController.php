<?php

namespace App\Http\Controllers\Cards;

use App\Services\CardService;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;

class DeckInitializeController
{
    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function __invoke(CardService $cardService): Response
    {
        return $cardService->initializeDeck();
    }
}
