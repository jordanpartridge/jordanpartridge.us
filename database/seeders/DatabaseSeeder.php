<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FeaturedPodcastSeeder::class,
            RolePermissionSeeder::class,
            // User needs to be seeded after roles and permissions
            UserSeeder::class,
            // Client seeder
            ClientSeeder::class,
        ]);
    }
}
