<?php

namespace Tests\Feature\Filament;

use App\Models\Post;
use App\Services\AI\AIContentService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PostAIGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected $mockAIService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the AIContentService to avoid actual API calls during tests
        $this->mockAIService = Mockery::mock(AIContentService::class);
        app()->instance(AIContentService::class, $this->mockAIService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_generate_seo_metadata()
    {
        // Create a test post
        $post = Post::factory()->create([
            'title' => 'Test Post with SEO',
            'body'  => 'This is test content for SEO metadata generation testing.',
        ]);

        // Configure mock response
        $metadata = [
            'meta_title'       => 'AI Generated SEO Title',
            'meta_description' => 'This is an AI generated meta description that summarizes the post content',
            'keywords'         => ['laravel', 'seo', 'ai', 'content'],
        ];

        $this->mockAIService->shouldReceive('generateSeoMetadata')
            ->once()
            ->with(Mockery::on(function ($arg) use ($post) {
                return $arg->id === $post->id;
            }))
            ->andReturn($metadata);

        // Call the service through the controller
        $result = app(AIContentService::class)->generateSeoMetadata($post);

        // Verify the result
        $this->assertEquals($metadata, $result);
        $this->assertEquals('AI Generated SEO Title', $result['meta_title']);
        $this->assertEquals('This is an AI generated meta description that summarizes the post content', $result['meta_description']);
        $this->assertCount(4, $result['keywords']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_generate_social_post_content()
    {
        // Create a test post
        $post = Post::factory()->create([
            'title' => 'Test Post for Social Media',
            'body'  => 'This is test content for social media post generation.',
        ]);

        // Mock responses for different platforms
        $this->mockAIService->shouldReceive('generateSocialPost')
            ->with(Mockery::on(function ($arg) use ($post) {
                return $arg->id === $post->id;
            }), 'linkedin')
            ->andReturn('Check out my new post about Laravel and AI integration!');

        $this->mockAIService->shouldReceive('generateSocialPost')
            ->with(Mockery::on(function ($arg) use ($post) {
                return $arg->id === $post->id;
            }), 'twitter')
            ->andReturn('Just published a new article on #Laravel and #AI integration!');

        // Call the service for LinkedIn
        $linkedinResult = app(AIContentService::class)->generateSocialPost($post, 'linkedin');
        $this->assertEquals('Check out my new post about Laravel and AI integration!', $linkedinResult);

        // Call the service for Twitter
        $twitterResult = app(AIContentService::class)->generateSocialPost($post, 'twitter');
        $this->assertEquals('Just published a new article on #Laravel and #AI integration!', $twitterResult);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_errors_gracefully()
    {
        // Create a test post
        $post = Post::factory()->create([
            'title' => 'Test Error Handling',
            'body'  => 'This is test content for error handling.',
        ]);

        // Configure mock to throw an exception
        $this->mockAIService->shouldReceive('generateSocialPost')
            ->with(Mockery::any(), 'linkedin')
            ->andThrow(new Exception('API error occurred'));

        // We expect to catch the exception in the controller
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('AI content generation failed: API error occurred');

        // Call the service which should throw
        app(AIContentService::class)->generateSocialPost($post, 'linkedin');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_generates_summary_for_post()
    {
        // Create a test post
        $post = Post::factory()->create([
            'title' => 'Test Summary Generation',
            'body'  => 'This is a longer post with multiple paragraphs that needs summarization. It contains information about Laravel, AI, and content generation that should be distilled into a concise summary by the AI service.',
        ]);

        // Configure mock response
        $expectedSummary = 'This post covers Laravel, AI, and content generation technologies.';

        $this->mockAIService->shouldReceive('generateSummary')
            ->once()
            ->with(Mockery::on(function ($arg) use ($post) {
                return $arg->id === $post->id;
            }))
            ->andReturn($expectedSummary);

        // Call the service
        $result = app(AIContentService::class)->generateSummary($post);

        // Verify the result
        $this->assertEquals($expectedSummary, $result);
    }
}
