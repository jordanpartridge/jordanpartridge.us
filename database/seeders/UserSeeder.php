<?php

namespace Database\Seeders;

use App\Events\UserUpdateOrCreated;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserUpdateOrCreated::fire(queryArray: ['email' => 'jordan@partridge.rocks'], updateArray: ['name' => 'Jordan Partridge', 'password' => bcrypt(random_bytes(10))]);
    }
}
