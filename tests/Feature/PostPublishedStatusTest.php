<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPublishedStatusTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function post_can_be_published_using_is_published_column()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a post with is_published = true
        $publishedPost = Post::factory()->create([
            'user_id'      => $user->id,
            'status'       => Post::STATUS_PUBLISHED,
            'is_published' => true,
        ]);

        // Create a post with is_published = false
        $draftPost = Post::factory()->create([
            'user_id'      => $user->id,
            'status'       => Post::STATUS_DRAFT,
            'is_published' => false,
        ]);

        // Test the published scope
        $publishedPosts = Post::published()->get();

        $this->assertCount(1, $publishedPosts);
        $this->assertTrue($publishedPosts->contains($publishedPost));
        $this->assertFalse($publishedPosts->contains($draftPost));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function post_can_be_published_using_set_published_method()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a draft post
        $post = Post::factory()->create([
            'user_id'      => $user->id,
            'status'       => Post::STATUS_DRAFT,
            'is_published' => false,
        ]);

        // Set it to published
        $post->setPublished(true);
        $post->save();

        // Reload from database
        $post->refresh();

        // Verify both status and is_published are set correctly
        $this->assertEquals(Post::STATUS_PUBLISHED, $post->status);
        $this->assertTrue($post->is_published);

        // Verify it appears in published scope
        $publishedPosts = Post::published()->get();
        $this->assertCount(1, $publishedPosts);
        $this->assertTrue($publishedPosts->contains($post));

        // Now set back to draft
        $post->setPublished(false);
        $post->save();
        $post->refresh();

        // Verify the values changed
        $this->assertEquals(Post::STATUS_DRAFT, $post->status);
        $this->assertFalse($post->is_published);

        // Verify it no longer appears in published scope
        $publishedPosts = Post::published()->get();
        $this->assertCount(0, $publishedPosts);
    }
}
