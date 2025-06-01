<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have a user and categories for the posts
        $user = User::first() ?? User::factory()->create([
            'name'  => 'Jordan Partridge',
            'email' => 'jordan@example.com',
        ]);

        // Create some categories
        $categories = Category::factory(5)->create();

        // Create published blog posts for testing
        $posts = Post::factory(10)->create([
            'user_id' => $user->id,
            'status'  => 'published',
            'active'  => 1,
        ]);

        // Attach categories to posts
        $posts->each(function (Post $post) use ($categories) {
            $post->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        // Create a featured post
        Post::factory()->create([
            'user_id'  => $user->id,
            'title'    => 'Welcome to Jordan\'s Blog',
            'status'   => 'published',
            'active'   => 1,
            'featured' => true,
            'excerpt'  => 'This is a featured blog post for testing purposes.',
        ]);
    }
}
