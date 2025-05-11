<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientDocument>
 */
class ClientDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = ClientDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id'         => Client::factory(),
            'uploaded_by'       => User::factory(),
            'filename'          => 'client-documents/' . $this->faker->uuid . '.pdf',
            'original_filename' => $this->faker->words(3, true) . '.pdf',
            'mime_type'         => 'application/pdf',
            'file_size'         => $this->faker->numberBetween(1024, 5242880), // 1KB to 5MB
            'description'       => $this->faker->optional()->sentence(),
        ];
    }
}
