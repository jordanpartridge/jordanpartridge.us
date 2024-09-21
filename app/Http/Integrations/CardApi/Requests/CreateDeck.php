<?php

namespace App\Http\Integrations\CardApi\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateDeck extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly string $name)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/decks';
    }

    public function defaultBody(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
