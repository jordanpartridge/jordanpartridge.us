<?php

namespace Database\Factories;

use App\Enums\ClientStatus;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'    => $this->faker->name(),
            'company' => $this->faker->company(),
            'email'   => $this->faker->unique()->safeEmail(),
            'phone'   => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'notes'   => $this->faker->text(),
            'status'  => $this->faker->randomElement(ClientStatus::values()),
            'user_id' => null,
        ];
    }

    /**
     * Assign the client to a user.
     *
     * @return $this
     */
    public function assignedToUser(?User $user = null): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user?->id ?? User::factory(),
        ]);
    }
}
