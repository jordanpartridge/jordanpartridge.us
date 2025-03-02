<?php

namespace Database\Factories;

use App\Models\GithubRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class GitHubRepositoryFactory extends Factory
{
    protected $model = GithubRepository::class;

    public function definition(): array
    {
        return [
            'name'            => $this->faker->unique()->words(3, true),
            'repository'      => $this->faker->userName . '/' . $this->faker->slug(2),
            'description'     => $this->faker->paragraph(),
            'url'             => $this->faker->url(),
            'technologies'    => ['PHP', 'Laravel', 'Tailwind', 'Alpine.js'],
            'featured'        => $this->faker->boolean(20),
            'display_order'   => $this->faker->numberBetween(1, 10),
            'is_active'       => true,
            'stars_count'     => $this->faker->numberBetween(0, 100),
            'forks_count'     => $this->faker->numberBetween(0, 30),
            'last_fetched_at' => now(),
        ];
    }

    public function featured(): self
    {
        return $this->state(function () {
            return [
                'featured' => true,
            ];
        });
    }
}
