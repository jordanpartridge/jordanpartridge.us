<?php

namespace Database\Seeders;

use App\Enums\ClientStatus;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Use existing admin if available, don't create a new one
        // This avoids the bcrypt hashing error
        $admin = User::where('email', 'admin@example.com')->first() ??
                 User::first() ??
                 User::factory()->create([
                     'name'  => 'Admin User',
                     'email' => 'admin@example.com'
                 ]);

        // Get other users or create them if needed
        $existingUsers = User::where('id', '!=', $admin->id)->take(3)->get();
        $users = $existingUsers->count() >= 3 ? $existingUsers : User::factory()->count(3 - $existingUsers->count())->create();
        $users->prepend($admin);

        // Create lead clients using enum values
        Client::factory()
            ->count(10)
            ->assignedToUser($users->random())
            ->create(['status' => ClientStatus::LEAD]);

        // Create active clients
        Client::factory()
            ->count(5)
            ->assignedToUser($users->random())
            ->create(['status' => ClientStatus::ACTIVE]);

        // Create former clients
        Client::factory()
            ->count(3)
            ->assignedToUser($users->random())
            ->create(['status' => ClientStatus::FORMER]);

        // Create unassigned clients
        Client::factory()
            ->count(4)
            ->create();
    }
}
