<?php

namespace App\Http\Integrations\CardApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetDeck extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private readonly string $name)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/decks/' . $this->name;
    }
}
