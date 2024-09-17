<?php

namespace App\Http\Integrations\CardApi;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class CardApi extends Connector
{
    public function __construct(string $token, private readonly string $base_url)
    {
        $this->authenticate(new TokenAuthenticator($token));
    }

    public function resolveBaseUrl(): string
    {
        return $this->base_url;
    }
}
