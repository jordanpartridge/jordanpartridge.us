<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection getUserRepositories(string $username, array $options = [])
 * @method static array|null getRepository(string $owner, string $repo)
 * @method static array getRepositoryTopics(string $owner, string $repo)
 * @method static array parseRepositoryUrl(string $url)
 */
class GitHub extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'github-client';
    }
}
