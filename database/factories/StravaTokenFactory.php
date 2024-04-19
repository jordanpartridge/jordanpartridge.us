<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StravaToken>
 */
class StravaTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'access_token' => $this->faker->word,
            'expires_at' => $this->faker->dateTime,
            'user_id' => User::factory()->create(),
            'refresh_token' => $this->faker->word,
            'athlete_id' => $this->faker->randomNumber(),
        ];
    }
}
