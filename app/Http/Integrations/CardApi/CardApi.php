<?php

namespace App\Http\Integrations\CardApi;

use Saloon\Http\Connector;

class CardApi extends Connector
{
    /**
     * Resolves the base URL for the Card API.
     *
     * @return string The base URL for the Card API.
     */
    public function resolveBaseUrl(): string
    {
        return 'https://card-api.jordanpartridge.us/v1/';
    }
}
