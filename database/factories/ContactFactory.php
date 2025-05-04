<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->safeEmail(),
            'phone'    => $this->faker->phoneNumber(),
            'reason'   => $this->faker->randomElement(['Freelance Project', 'Teaching Opportunity', 'Collaboration', 'Other']),
            'budget'   => $this->faker->optional()->randomElement(['$1,000 - $5,000', '$5,000 - $10,000', '$10,000+']),
            'timeline' => $this->faker->optional()->randomElement(['ASAP', '1-3 months', '3-6 months', 'Flexible']),
            'message'  => $this->faker->paragraph(),
        ];
    }
}
