<?php

namespace App\Http\Integrations\CardApi\Requests;

use Illuminate\Support\Str;
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

        if (Str::length($this->deckName) > 255) {
            throw new InvalidArgumentException('Deck name must be less than 255 characters');
        }
    }

    public function resolveEndpoint(): string
    {
        return '/decks/' . $this->deckName . '/draw?count=' . $this->cardCount;
    }
}
