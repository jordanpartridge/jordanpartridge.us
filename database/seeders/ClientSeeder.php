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
        // Get the first admin user for assignment, or create one if none exists
        $user = User::first();

        // If no users exist, create a default admin user
        if (!$user) {
            $user = User::factory()->create([
                'name'  => 'Admin User',
                'email' => 'admin@example.com',
            ]);
        }

        // Create Sam Gray as the primary client
        Client::create([
            'name'            => 'Sam Gray',
            'company'         => 'Gray Enterprises',
            'email'           => 'sam.gray@example.com',
            'phone'           => '+15555555555',
            'website'         => 'https://samgray.example.com',
            'status'          => ClientStatus::ACTIVE->value,
            'is_focused'      => true,
            'user_id'         => $user->id, // Now we can safely use $user->id
            'last_contact_at' => now()->subDays(3),
            'notes'           => '<p>Sam is working on a website redesign project and an SEO campaign.</p><p>Key points from our last call:</p><ul><li>Prefers communication via email</li><li>Weekly status meetings on Thursdays</li><li>Invoice at the end of each month</li></ul>',
        ]);

        // Create a few more example clients if needed in development
        if (app()->environment(['local', 'development', 'testing'])) {
            // Get additional users if they exist (up to 3)
            $users = User::take(3)->get();

            // If we have no users or just one, ensure we at least have the admin user
            if ($users->count() <= 1) {
                $users = collect([$user]);
            }

            // Create clients with LEAD status
            Client::factory()->count(2)->create([
                'status'          => ClientStatus::LEAD->value,
                'user_id'         => $users->random()->id,
                'last_contact_at' => now()->subDays(rand(1, 5)),
            ]);

            // Create clients with ACTIVE status
            Client::factory()->count(2)->create([
                'status'          => ClientStatus::ACTIVE->value,
                'user_id'         => $users->random()->id,
                'last_contact_at' => now()->subDays(rand(1, 15)),
            ]);

            // Create clients with FORMER status
            Client::factory()->count(1)->create([
                'status'          => ClientStatus::FORMER->value,
                'user_id'         => $users->random()->id,
                'last_contact_at' => now()->subMonths(rand(1, 6)),
            ]);
        }
    }
}
