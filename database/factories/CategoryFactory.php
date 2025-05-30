<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'color'       => fake()->hexColor(),
        ];
    }
}
