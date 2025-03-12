<?php

namespace App\Http\Integrations\CardApi;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class CardApi extends Connector
{
    public function __construct(?string $token = null, private readonly ?string $base_url = null)
    {
        if ($token) {
            $this->authenticate(new TokenAuthenticator($token));
        }
    }

    public function resolveBaseUrl(): string
    {
        return $this->base_url ?? 'https://card-api.jordanpartridge.com/api/v1';
    }
}
