<?php

namespace App\Http\Integrations\GitHubApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetRepositories extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly string $username,
        private readonly int $perPage = 100,
        private readonly int $page = 1,
        private readonly string $sort = 'updated',
        private readonly string $direction = 'desc'
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/users/' . $this->username . '/repos';
    }

    protected function defaultQuery(): array
    {
        return [
            'per_page'  => $this->perPage,
            'page'      => $this->page,
            'sort'      => $this->sort,
            'direction' => $this->direction,
        ];
    }
}
