<?php

namespace App\Http\Integrations\CardApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class CreateDeck extends Request
{
    protected Method $method = Method::POST;

    public function __construct(private readonly string $name)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/decks';
    }

    public function defaultData(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
