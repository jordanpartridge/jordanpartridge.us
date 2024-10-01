<?php

namespace Database\Factories;

use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'    => fake()->words(3, true),
            'game_id' => Snowflake::make(),
        ];
    }
}
