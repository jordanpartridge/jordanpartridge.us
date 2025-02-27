<?php

namespace App\Http\Integrations\GitHubApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetRepository extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly string $username,
        private readonly string $repository
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/repos/' . $this->username . '/' . $this->repository;
    }
}
