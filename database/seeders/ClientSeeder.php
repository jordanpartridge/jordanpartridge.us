<?php

namespace Database\Seeders;

use App\Enums\ClientStatus;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user for assignment
        $user = User::first();

        // Create Sam Gray as the primary client
        Client::create([
            'name'            => 'Sam Gray',
            'company'         => 'Gray Enterprises',
            'email'           => 'sam.gray@example.com',
            'phone'           => '+15555555555',
            'website'         => 'https://samgray.example.com',
            'status'          => ClientStatus::ACTIVE->value,
            'user_id'         => $user?->id,
            'last_contact_at' => now()->subDays(3),
            'notes'           => '<p>Sam is working on a website redesign project and an SEO campaign.</p><p>Key points from our last call:</p><ul><li>Prefers communication via email</li><li>Weekly status meetings on Thursdays</li><li>Invoice at the end of each month</li></ul>',
        ]);

        // Create a few more example clients if needed in development
        if (app()->environment(['local', 'development', 'testing'])) {
            Client::factory()->count(5)->create([
                'user_id' => $user?->id,
            ]);
        }
    }
}
