<?php

namespace App\Services;

use App\Http\Integrations\CardApi\CardApi;
use App\Http\Integrations\CardApi\Requests\CreateDeck;
use App\Http\Integrations\CardApi\Requests\GetDeck;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

final readonly class CardService
{
    public function __construct(
        private CardApi $cardApi,
        private string $deckName = 'jordan-partridge-us',
    ) {
    }

    public function getDeck(): array
    {
        return $this->cardApi->send(new GetDeck($this->deckName))->json();
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException|\JsonException
     */
    public function initializeDeck(): array
    {
        $existingDeck = $this->getDeck($this->deckName);
        if ($existingDeck['name'] === $this->deckName) {
            return $existingDeck;
        }

        return $this->cardApi->send(new CreateDeck($this->deckName))->json();
    }
}
