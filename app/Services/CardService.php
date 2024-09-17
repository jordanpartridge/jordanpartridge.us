<?php

namespace App\Services;

use App\Http\Integrations\CardApi\CardApi;
use App\Http\Integrations\CardApi\Requests\CreateDeck;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

final class CardService
{
    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function __construct(private readonly CardApi $cardApi)
    {
    }

    public function getDeck(): array
    {
        return $this->cardApi->send(new GetDeck())->json();
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function initializeDeck(): void
    {
        $this->cardApi->send(new CreateDeck('jordan-partridge-us-deck'));
    }
}
