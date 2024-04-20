<?php

namespace Database\Factories;

use App\Models\Ride;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ride>
 */
class RideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => $this->faker->word,
            'distance'      => $this->faker->randomFloat(2, 0, 1000),
            'max_speed'     => $this->faker->randomFloat(2, 0, 100),
            'average_speed' => $this->faker->randomFloat(2, 0, 100),
            'date'          => $this->faker->dateTime,
            'external_id'   => $this->faker->uuid,
        ];
    }
}
