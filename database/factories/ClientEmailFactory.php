<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientEmail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientEmail>
 */
class ClientEmailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = ClientEmail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id'        => Client::factory(),
            'gmail_message_id' => $this->faker->uuid(),
            'subject'          => $this->faker->sentence(),
            'from'             => $this->faker->email(),
            'to'               => [$this->faker->email()],
            'cc'               => $this->faker->boolean(30) ? [$this->faker->email()] : null,
            'bcc'              => $this->faker->boolean(20) ? [$this->faker->email()] : null,
            'snippet'          => $this->faker->paragraph(),
            'body'             => $this->faker->randomHtml(),
            'label_ids'        => $this->faker->boolean(70) ? ['INBOX', 'IMPORTANT'] : null,
            'raw_payload'      => null,
            'email_date'       => $this->faker->dateTimeThisYear(),
            'synchronized_at'  => now(),
        ];
    }
}
