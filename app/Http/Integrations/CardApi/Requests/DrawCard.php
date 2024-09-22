<?php

namespace App\Http\Integrations\CardApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DrawCard extends Request
{
    protected Method $method = Method::PUT;

    public function __construct(private readonly string $deckName, private readonly int $number = 1)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/decks/' . $this->deckName . '/draw?count=' . $this->number;
    }
}
