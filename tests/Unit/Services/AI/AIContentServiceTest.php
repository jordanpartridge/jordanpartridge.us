<?php

namespace Tests\Unit\Services\AI;

use App\Models\Post;
use App\Models\PromptTemplate;
use App\Services\AI\AIContentService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Mockery;
use Prism\Prism;
use Prism\PrismResponse;
use Tests\TestCase;

class AIContentServiceTest extends TestCase
{
    use RefreshDatabase;

    private $mockPrism;
    private $mockResponse;
    private $service;
    private $post;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock Prism service
        $this->mockPrism = Mockery::mock(Prism::class);

        // Mock PrismResponse
        $this->mockResponse = Mockery::mock(PrismResponse::class);

        // Create the service with the mock
        $this->service = new AIContentService($this->mockPrism);

        // Create a test post
        $this->post = Post::factory()->create([
            'title'       => 'Test Post Title',
            'description' => 'This is a test post description',
            'content'     => 'This is the test post content. It contains multiple sentences and paragraphs to simulate a real blog post. We want to make sure that the AI content generation service can properly process the content and generate appropriate responses based on this input.',
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_generate_social_post_using_default_template()
    {
        // Expected content to be returned by the AI
        $expectedContent = "This is a generated social media post for LinkedIn about Test Post Title.";

        // Configure the mock to expect the run method to be called
        $this->mockResponse->shouldReceive('content')
            ->once()
            ->andReturn($expectedContent);

        $this->mockPrism->shouldReceive('run')
            ->once()
            ->withArgs(function ($arguments) {
                // Verify model and messages structure
                return $arguments['model'] === 'ollama/mistral:latest'
                    && count($arguments['messages']) === 2
                    && $arguments['messages'][0]['role'] === 'system'
                    && $arguments['messages'][1]['role'] === 'user'
                    && $arguments['temperature'] === 0.7
                    && $arguments['max_tokens'] === 500;
            })
            ->andReturn($this->mockResponse);

        // Call the service
        $result = $this->service->generateSocialPost($this->post, 'linkedin');

        // Assert the result matches the expected content
        $this->assertEquals($expectedContent, $result);
    }

    /** @test */
    public function it_can_generate_social_post_using_database_template()
    {
        // Create a database template
        $template = PromptTemplate::create([
            'name'          => 'LinkedIn Post Template',
            'key'           => 'social_post',
            'platform'      => 'linkedin',
            'system_prompt' => 'You are a LinkedIn post creator',
            'user_prompt'   => 'Create a post about {title}',
            'content_type'  => 'social_post',
            'variables'     => ['title', 'description', 'excerpt', 'platform'],
            'parameters'    => [
                'temperature' => 0.5,
                'max_tokens'  => 300
            ],
            'is_active' => true,
        ]);

        // Expected content
        $expectedContent = "This is a generated social media post from the database template.";

        // Configure the mock response
        $this->mockResponse->shouldReceive('content')
            ->once()
            ->andReturn($expectedContent);

        $this->mockPrism->shouldReceive('run')
            ->once()
            ->withArgs(function ($arguments) {
                // Verify the database template is used
                return $arguments['model'] === 'ollama/mistral:latest'
                    && $arguments['messages'][0]['content'] === 'You are a LinkedIn post creator'
                    && $arguments['messages'][1]['content'] === 'Create a post about Test Post Title'
                    && $arguments['temperature'] === 0.5
                    && $arguments['max_tokens'] === 300;
            })
            ->andReturn($this->mockResponse);

        // Call the service
        $result = $this->service->generateSocialPost($this->post, 'linkedin');

        // Assert the result
        $this->assertEquals($expectedContent, $result);
    }

    /** @test */
    public function it_uses_fallback_content_when_api_throws_exception()
    {
        // Configure the mock to throw an exception
        $this->mockPrism->shouldReceive('run')
            ->once()
            ->andThrow(new Exception('API Error'));

        // Disable fallback model for this test
        Config::set('services.prism.fallback_model', null);

        // Call the service
        $result = $this->service->generateSocialPost($this->post, 'linkedin');

        // Assert that fallback content is returned
        $this->assertStringContainsString("I'm excited to share my latest article: \"Test Post Title\"", $result);
        $this->assertStringContainsString("This is a test post description", $result);
    }

    /** @test */
    public function it_can_generate_post_summary()
    {
        // Expected summary
        $expectedSummary = "This is a summary of the blog post.";

        // Configure the mock
        $this->mockResponse->shouldReceive('content')
            ->once()
            ->andReturn($expectedSummary);

        $this->mockPrism->shouldReceive('run')
            ->once()
            ->withArgs(function ($arguments) {
                return $arguments['model'] === 'ollama/mistral:latest'
                    && $arguments['max_tokens'] === 150;
            })
            ->andReturn($this->mockResponse);

        // Call the service
        $result = $this->service->generateSummary($this->post);

        // Assert the result
        $this->assertEquals($expectedSummary, $result);
    }

    /** @test */
    public function it_can_generate_seo_metadata()
    {
        // Expected metadata as JSON string
        $expectedMetadata = json_encode([
            'meta_title'       => 'Test Post Title | Optimized for SEO',
            'meta_description' => 'This is a test post description with SEO optimization',
            'keywords'         => ['test', 'seo', 'laravel']
        ]);

        // Configure the mock
        $this->mockResponse->shouldReceive('content')
            ->once()
            ->andReturn($expectedMetadata);

        $this->mockPrism->shouldReceive('run')
            ->once()
            ->withArgs(function ($arguments) {
                return $arguments['response_format']['type'] === 'json_object';
            })
            ->andReturn($this->mockResponse);

        // Call the service
        $result = $this->service->generateSeoMetadata($this->post);

        // Assert the result is an array with expected keys
        $this->assertIsArray($result);
        $this->assertArrayHasKey('meta_title', $result);
        $this->assertArrayHasKey('meta_description', $result);
        $this->assertArrayHasKey('keywords', $result);
    }

    /** @test */
    public function it_attempts_to_use_fallback_model_when_primary_fails()
    {
        // Set a fallback model
        Config::set('services.prism.fallback_model', 'ollama/mistral:7b-instruct-q4_0');

        // Expected content
        $expectedContent = "This is generated by the fallback model.";

        // Configure the mock to throw an exception on first call, then succeed on second call
        $this->mockPrism->shouldReceive('run')
            ->once() // First call with primary model
            ->andThrow(new Exception('API Error'))
            ->shouldReceive('run')
            ->once() // Second call with fallback model
            ->andReturn($this->mockResponse);

        $this->mockResponse->shouldReceive('content')
            ->once()
            ->andReturn($expectedContent);

        // Call the service
        $result = $this->service->generateSocialPost($this->post, 'linkedin');

        // Assert the result
        $this->assertEquals($expectedContent, $result);
    }
}
