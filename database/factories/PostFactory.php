<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
final class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'title'            => fake()->sentence(),
            'body'             => fake()->paragraphs(10, true),
            'image'            => 'https://picsum.photos/1280/720?random=' . fake()->unique()->slug(),
            'slug'             => fake()->unique()->slug(),
            'excerpt'          => fake()->optional()->word,
            'type'             => fake()->word,
            'status'           => 'published',
            'is_published'     => true, // Default to published
            'active'           => 1,
            'featured'         => fake()->boolean,
            'meta_title'       => fake()->optional()->word,
            'meta_description' => fake()->optional()->word,
            'meta_schema'      => fake()->optional()->text,
            'meta_data'        => fake()->optional()->text,
        ];
    }

    /**
     * Configure the model factory to create a draft post.
     */
    public function draft(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status'       => Post::STATUS_DRAFT,
                'is_published' => false,
            ];
        });
    }

    /**
     * Configure the model factory to create a published post.
     */
    public function published(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status'       => Post::STATUS_PUBLISHED,
                'is_published' => true,
            ];
        });
    }
}
