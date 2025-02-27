<?php

namespace App\Http\Integrations\GitHubApi;

use Saloon\Http\Connector;

class GitHubApi extends Connector
{
    public function __construct(private readonly ?string $token = null)
    {
        if ($this->token) {
            $this->withTokenAuth($this->token);
        }
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.github.com';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept'     => 'application/vnd.github.v3+json',
            'User-Agent' => 'jordanpartridge.us',
        ];
    }
}
