<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_belongs_to_a_user()
    {
        $post = Post::factory()->create();

        $this->assertInstanceOf(User::class, $post->user);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_be_scoped_to_published_posts()
    {
        // Create test posts with different statuses
        Post::factory()->create(['status' => Post::STATUS_PUBLISHED]);
        Post::factory()->create(['status' => Post::STATUS_DRAFT]);

        $publishedPosts = Post::published()->get();

        $this->assertCount(1, $publishedPosts);
        $this->assertEquals(Post::STATUS_PUBLISHED, $publishedPosts->first()->status);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_be_scoped_to_exclude_featured_posts()
    {
        // Create featured and non-featured posts
        Post::factory()->create([
            'featured' => true,
            'type'     => 'post'
        ]);

        Post::factory()->create([
            'featured' => false,
            'type'     => 'post'
        ]);

        $nonFeaturedPosts = Post::excludeFeatured()->get();

        $this->assertCount(1, $nonFeaturedPosts);
        $this->assertFalse((bool) $nonFeaturedPosts->first()->featured);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_be_scoped_to_posts_only()
    {
        // Create posts of different types
        Post::factory()->create(['type' => 'post']);
        Post::factory()->create(['type' => 'page']);

        $posts = Post::typePost()->get();

        $this->assertCount(1, $posts);
        $this->assertEquals('post', $posts->first()->type);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_be_listed_with_combined_scopes()
    {
        // Create various posts
        Post::factory()->create([
            'status'   => Post::STATUS_PUBLISHED,
            'type'     => 'post',
            'featured' => false
        ]);

        Post::factory()->create([
            'status'   => Post::STATUS_DRAFT,
            'type'     => 'post',
            'featured' => false
        ]);

        Post::factory()->create([
            'status'   => Post::STATUS_PUBLISHED,
            'type'     => 'page',
            'featured' => false
        ]);

        Post::factory()->create([
            'status'   => Post::STATUS_PUBLISHED,
            'type'     => 'post',
            'featured' => true
        ]);

        $listedPosts = Post::list()->get();

        $this->assertCount(1, $listedPosts);
        $this->assertEquals(Post::STATUS_PUBLISHED, $listedPosts->first()->status);
        $this->assertEquals('post', $listedPosts->first()->type);
        $this->assertFalse((bool) $listedPosts->first()->featured);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_generates_slug_from_title()
    {
        $post = Post::factory()->create([
            'title' => 'Test Blog Post Title',
            'slug'  => null
        ]);

        $this->assertEquals('test-blog-post-title', $post->slug);
    }
}
