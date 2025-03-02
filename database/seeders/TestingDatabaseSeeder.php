<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class TestingDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create test users
        User::factory(3)->create();

        // Create test posts
        Post::factory(5)->create([
            'user_id' => 1,
            'status'  => 'published'
        ]);

        // Create test comments
        Comment::factory(10)->create([
            'post_id' => 1,
            'user_id' => 1
        ]);
    }
}
