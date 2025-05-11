<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RssFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_rss_feed_returns_correct_content_type()
    {
        $response = $this->get('/feed.xml');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/xml');
    }

    public function test_rss_feed_contains_published_posts()
    {
        // Create some test posts
        $publishedPost = Post::factory()->create([
            'status' => 'published',
            'title' => 'Test Published Post'
        ]);

        $draftPost = Post::factory()->create([
            'status' => 'draft',
            'title' => 'Test Draft Post'
        ]);

        $response = $this->get('/feed.xml');
        
        $response->assertStatus(200)
            ->assertSee($publishedPost->title)
            ->assertDontSee($draftPost->title);
    }

    public function test_rss_feed_has_valid_structure()
    {
        $response = $this->get('/feed.xml');
        
        $response->assertStatus(200)
            ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
            ->assertSee('<rss version="2.0"', false)
            ->assertSee('<channel>', false)
            ->assertSee('<title>', false)
            ->assertSee('<link>', false)
            ->assertSee('<description>', false)
            ->assertSee('</channel>', false)
            ->assertSee('</rss>', false);
    }
}