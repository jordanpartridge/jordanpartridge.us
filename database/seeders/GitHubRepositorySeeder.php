<?php

namespace Database\Seeders;

use App\Models\GithubRepository;
use Illuminate\Database\Seeder;

class GitHubRepositorySeeder extends Seeder
{
    public function run(): void
    {
        // Predefined repositories
        $repositories = [
            [
                'name'          => 'Laravel Packagist Client',
                'repository'    => 'jordanpartridge/packagist-client',
                'description'   => 'Simple integration library for the Packagist API, allowing seamless access to PHP package information, downloads, and statistics.',
                'url'           => 'https://github.com/jordanpartridge/packagist-client',
                'technologies'  => ['PHP', 'Laravel', 'Composer', 'API Integration'],
                'featured'      => true,
                'display_order' => 1,
                'stars_count'   => 8,
                'forks_count'   => 2,
            ],
            [
                'name'          => 'User Make',
                'repository'    => 'jordanpartridge/user-make',
                'description'   => 'Laravel package that extends the built-in user management with advanced role-based permissions, team capabilities, and custom authentication options.',
                'url'           => 'https://github.com/jordanpartridge/user-make',
                'technologies'  => ['Laravel', 'PHP', 'Authentication', 'Authorization'],
                'featured'      => true,
                'display_order' => 2,
                'stars_count'   => 12,
                'forks_count'   => 4,
            ],
            [
                'name'          => 'GitHub API Client',
                'repository'    => 'jordanpartridge/github-client',
                'description'   => 'An elegant GitHub API integration built for Laravel, providing a clean interface for accessing repositories, issues, pull requests, and more.',
                'url'           => 'https://github.com/jordanpartridge/github-client',
                'technologies'  => ['Laravel', 'PHP', 'GitHub API', 'API Client'],
                'featured'      => true,
                'display_order' => 3,
                'stars_count'   => 10,
                'forks_count'   => 3,
            ],
            [
                'name'          => 'Laravel Deployment Toolkit',
                'repository'    => 'jordanpartridge/laravel-deployment-toolkit',
                'description'   => 'An all-in-one solution for deploying Laravel applications to different environments with automated testing, database migrations, and rollback capabilities.',
                'url'           => 'https://github.com/jordanpartridge/laravel-deployment-toolkit',
                'technologies'  => ['Laravel', 'DevOps', 'CI/CD', 'Bash'],
                'featured'      => true,
                'display_order' => 4,
                'stars_count'   => 15,
                'forks_count'   => 7,
            ],
        ];

        foreach ($repositories as $repo) {
            GithubRepository::create([
                'name'            => $repo['name'],
                'repository'      => $repo['repository'],
                'description'     => $repo['description'],
                'url'             => $repo['url'],
                'technologies'    => $repo['technologies'],
                'featured'        => $repo['featured'],
                'display_order'   => $repo['display_order'],
                'is_active'       => true,
                'stars_count'     => $repo['stars_count'],
                'forks_count'     => $repo['forks_count'],
                'last_fetched_at' => now(),
            ]);
        }

        // Add some random repositories for variety
        GithubRepository::factory()->count(3)->create();
    }
}
