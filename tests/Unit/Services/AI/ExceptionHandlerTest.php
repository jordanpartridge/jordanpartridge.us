<?php

namespace Tests\Unit\Services\AI;

use App\Models\FailedTask;
use App\Models\Post;
use App\Services\AI\Exceptions\AIGenerationException;
use App\Services\AI\Handlers\ExceptionHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExceptionHandlerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ExceptionHandler
     */
    protected $handler;

    protected function setUp(): void
    {
        parent::setUp();

        // Make sure we run the migrations
        $this->artisan('migrate');

        // Create the handler
        $this->handler = new ExceptionHandler();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_formats_error_message_with_required_prefix()
    {
        // Create an exception
        $exception = new AIGenerationException(
            'generateSocialPost',
            123,
            'linkedin',
            'API error occurred'
        );

        // Message should have the required prefix
        $this->assertStringStartsWith(
            'AI content generation failed:',
            $exception->getMessage()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_records_error_to_failed_tasks_table()
    {
        // Skip if the failed_tasks table doesn't have the new schema
        if (!Schema::hasColumn('failed_tasks', 'task_type')) {
            $this->markTestSkipped('Failed tasks table is not migrated yet.');
            return;
        }

        // Initial count
        $initialCount = FailedTask::count();

        // Record an error
        $this->handler->recordError(
            'generateSocialPost',
            123,
            'linkedin',
            'API error occurred'
        );

        // Verify a record was created
        $this->assertEquals($initialCount + 1, FailedTask::count());

        // Verify the content of the record
        $failedTask = FailedTask::latest()->first();
        $this->assertEquals('ai.generateSocialPost', $failedTask->task_type);
        $this->assertEquals('post', $failedTask->entity_type);
        $this->assertEquals(123, $failedTask->entity_id);
        $this->assertEquals('API error occurred', $failedTask->error);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_properly_handles_exceptions()
    {
        // Skip if the failed_tasks table doesn't have the new schema
        if (!Schema::hasColumn('failed_tasks', 'task_type')) {
            $this->markTestSkipped('Failed tasks table is not migrated yet.');
            return;
        }

        // Create an exception
        $exception = new AIGenerationException(
            'generateSocialPost',
            123,
            'linkedin',
            'API error occurred'
        );

        // Initial count
        $initialCount = FailedTask::count();

        // Handle the exception
        $this->handler->handleException($exception);

        // Verify a record was created
        $this->assertEquals($initialCount + 1, FailedTask::count());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_provides_fallback_content_for_posts()
    {
        // Create a mock post
        $post = new Post([
            'title'       => 'Test Post',
            'description' => 'Test Description'
        ]);

        // Get fallback content for social post
        $socialContent = $this->handler->getFallbackContent('generateSocialPost', $post, 'linkedin');

        // Verify it contains the post title
        $this->assertStringContainsString('Test Post', $socialContent);

        // Get fallback content for summary
        $summaryContent = $this->handler->getFallbackContent('generateSummary', $post);

        // Verify it contains the post title
        $this->assertStringContainsString('Test Post', $summaryContent);

        // Get fallback content for SEO metadata
        $seoContent = $this->handler->getFallbackContent('generateSeoMetadata', $post);

        // Verify it's an array with the expected keys
        $this->assertIsArray($seoContent);
        $this->assertArrayHasKey('meta_title', $seoContent);
        $this->assertEquals('Test Post', $seoContent['meta_title']);
    }
}
