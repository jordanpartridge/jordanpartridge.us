<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FailedTask>
 */
class FailedTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_type'   => $this->faker->randomElement(['ai.generateSocialPost', 'ai.generateSummary', 'ai.generateSeoMetadata']),
            'entity_type' => 'post',
            'entity_id'   => Post::factory(),
            'error'       => $this->faker->sentence(),
            'context'     => [
                'platform'    => $this->faker->randomElement(['linkedin', 'twitter', null]),
                'is_fallback' => $this->faker->boolean(),
            ],
            'attempts' => $this->faker->numberBetween(1, 5),
        ];
    }
}
