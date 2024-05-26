<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Suit>
 */
class SuitFactory extends Factory
{
    private array $presets = [
        ['name' => 'Hearts', 'color' => 'Red', 'symbol' => '♥'],
        ['name' => 'Diamonds', 'color' => 'Red', 'symbol' => '♦'],
        ['name' => 'Clubs', 'color' => 'Black', 'symbol' => '♣'],
        ['name' => 'Spades', 'color' => 'Black', 'symbol' => '♠'],
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'   => $this->faker->randomElement(['Hearts', 'Diamonds', 'Clubs', 'Spades']),
            'color'  => $this->faker->randomElement(['Red', 'Black']),
            'symbol' => $this->faker->randomElement(['♥', '♦', '♣', '♠']),
        ];
    }
}
