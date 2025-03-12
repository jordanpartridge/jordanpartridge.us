<?php

namespace Database\Seeders;

use App\Models\GithubRepository;
use Illuminate\Database\Seeder;

class GitHubRepositorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $repositories = [
            [
                'name'            => 'packagist-client',
                'repository'      => 'jordanpartridge/packagist-client',
                'description'     => 'Simple integration library for the Packagist API, allowing seamless access to PHP package information, downloads, and statistics.',
                'url'             => 'https://github.com/jordanpartridge/packagist-client',
                'technologies'    => ['PHP', 'Laravel', 'Composer', 'API Integration'],
                'featured'        => true,
                'display_order'   => 1,
                'is_active'       => true,
                'stars_count'     => 8,
                'forks_count'     => 2,
                'last_fetched_at' => now()->subDays(28),
            ],
            [
                'name'            => 'user-make',
                'repository'      => 'jordanpartridge/user-make',
                'description'     => 'Laravel package that extends the built-in user management with advanced role-based permissions, team capabilities, and custom authentication options.',
                'url'             => 'https://github.com/jordanpartridge/user-make',
                'technologies'    => ['Laravel', 'PHP', 'Authentication', 'Authorization'],
                'featured'        => true,
                'display_order'   => 2,
                'is_active'       => true,
                'stars_count'     => 12,
                'forks_count'     => 4,
                'last_fetched_at' => now()->subDays(63),
            ],
            [
                'name'            => 'github-client',
                'repository'      => 'jordanpartridge/github-client',
                'description'     => 'An elegant GitHub API integration built for Laravel, providing a clean interface for accessing repositories, issues, pull requests, and more.',
                'url'             => 'https://github.com/jordanpartridge/github-client',
                'technologies'    => ['Laravel', 'PHP', 'GitHub API', 'API Client'],
                'featured'        => true,
                'display_order'   => 3,
                'is_active'       => true,
                'stars_count'     => 10,
                'forks_count'     => 3,
                'last_fetched_at' => now()->subDays(10),
            ],
            [
                'name'            => 'laravel-deployment-toolkit',
                'repository'      => 'jordanpartridge/laravel-deployment-toolkit',
                'description'     => 'An all-in-one solution for deploying Laravel applications to different environments with automated testing, database migrations, and rollback capabilities.',
                'url'             => 'https://github.com/jordanpartridge/laravel-deployment-toolkit',
                'technologies'    => ['Laravel', 'DevOps', 'CI/CD', 'Bash'],
                'featured'        => true,
                'display_order'   => 4,
                'is_active'       => true,
                'stars_count'     => 15,
                'forks_count'     => 7,
                'last_fetched_at' => now()->subDays(76),
            ],
        ];

        foreach ($repositories as $repository) {
            GithubRepository::updateOrCreate(
                ['repository' => $repository['repository']],
                $repository
            );
        }
    }
}
