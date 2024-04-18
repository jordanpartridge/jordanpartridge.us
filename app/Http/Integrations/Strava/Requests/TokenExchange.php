<?php

namespace App\Http\Integrations\Strava\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class TokenExchange extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The code from the Strava OAuth flow
     */
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/oauth/token';
    }

    /**
     * The request body
     */
    public function defaultBody(): array
    {
        return [
            'client_id' => config('services.strava.client_id'),
            'client_secret' => config('services.strava.client_secret'),
            'code' => $this->code,
            'grant_type' => 'authorization_code',
        ];
    }
}
