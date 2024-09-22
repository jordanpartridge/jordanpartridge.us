<?php

namespace App\Http\Integrations\CardApi\Requests;

use InvalidArgumentException;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class DrawCard extends Request
{
    protected Method $method = Method::PUT;

    public function __construct(private readonly string $deckName, private readonly int $cardCount = 1)
    {
        if ($cardCount < 1) {
            throw new InvalidArgumentException('Card count must be greater than 0');
        }

        if (! preg_match('/^[a-zA-Z0-9-]+$/', $this->deckName)) {
            throw new InvalidArgumentException('Deck name must be a valid slug');
        }
    }

    public function resolveEndpoint(): string
    {
        return '/decks/' . $this->deckName . '/draw?count=' . $this->cardCount;
    }
}
