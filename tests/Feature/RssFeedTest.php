<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('rss feed returns correct content type', function () {
    $response = $this->get('/feed.xml');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'application/xml');
});

test('rss feed contains published posts and excludes drafts', function () {
    // Create some test posts
    $publishedPost = Post::factory()->create([
        'status' => 'published',
        'title'  => 'Test Published Post'
    ]);

    $draftPost = Post::factory()->create([
        'status' => 'draft',
        'title'  => 'Test Draft Post'
    ]);

    $response = $this->get('/feed.xml');

    $response->assertStatus(200)
        ->assertSee($publishedPost->title)
        ->assertDontSee($draftPost->title);
});

test('rss feed has valid xml structure', function () {
    $response = $this->get('/feed.xml');
    $content = $response->getContent();

    $response->assertStatus(200);

    // Check main RSS elements
    expect($content)->toContain('<rss version="2.0"')
        ->toContain('xmlns:content="http://purl.org/rss/1.0/modules/content/"')
        ->toContain('xmlns:atom="http://www.w3.org/2005/Atom"')
        ->toContain('<channel>')
        ->toContain('<title>')
        ->toContain('<link>')
        ->toContain('<description>')
        ->toContain('<language>en-us</language>')
        ->toContain('<pubDate>')
        ->toContain('<lastBuildDate>')
        ->toContain('<atom:link')
        ->toContain('</channel>')
        ->toContain('</rss>');
});

test('rss feed limits to 20 most recent published posts', function () {
    // Create 25 published posts
    Post::factory()->count(25)->create([
        'status'     => 'published',
        'created_at' => now()->subDay(),
    ]);

    // Create one newest post
    $newestPost = Post::factory()->create([
        'status'     => 'published',
        'title'      => 'Newest Published Post',
        'created_at' => now(),
    ]);

    // Create one oldest post
    $oldestPost = Post::factory()->create([
        'status'     => 'published',
        'title'      => 'Oldest Published Post',
        'created_at' => now()->subDays(30),
    ]);

    $response = $this->get('/feed.xml');

    // Should include newest post
    $response->assertSee($newestPost->title);

    // Should exclude oldest post (since we have 27 posts total, and limit is 20)
    $response->assertDontSee($oldestPost->title);

    // Count the number of <item> tags
    $content = $response->getContent();
    $itemCount = substr_count($content, '<item>');
    expect($itemCount)->toBe(20);
});
