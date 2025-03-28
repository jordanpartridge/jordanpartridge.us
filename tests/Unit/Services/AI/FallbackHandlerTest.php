<?php

namespace Tests\Unit\Services\AI;

use App\Services\AI\Exceptions\AIGenerationException;
use App\Services\AI\Handlers\ExceptionHandler;
use App\Services\AI\Handlers\FallbackHandler;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Prism\Prism;
use Prism\PrismResponse;
use Tests\TestCase;

class FallbackHandlerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var FallbackHandler
     */
    protected $handler;

    /**
     * @var Mockery\MockInterface
     */
    protected $mockPrism;

    /**
     * @var Mockery\MockInterface
     */
    protected $mockExceptionHandler;

    /**
     * @var Mockery\MockInterface
     */
    protected $mockResponse;

    protected function setUp(): void
    {
        parent::setUp();

        // Create mocks
        $this->mockPrism = Mockery::mock(Prism::class);
        $this->mockExceptionHandler = Mockery::mock(ExceptionHandler::class);
        $this->mockResponse = Mockery::mock(PrismResponse::class);

        // Create the handler
        $this->handler = new FallbackHandler(
            $this->mockPrism,
            $this->mockExceptionHandler
        );

        // Set fallback model for testing
        config(['services.prism.fallback_model' => 'fallback-model']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_attempts_to_use_fallback_model_when_available()
    {
        // Original exception
        $originalException = new Exception('Original error');

        // Expected content
        $expectedContent = 'Fallback content';

        // Setup mocks
        $this->mockResponse->shouldReceive('content')
            ->once()
            ->andReturn($expectedContent);

        $this->mockPrism->shouldReceive('run')
            ->once()
            ->withArgs(function ($params) {
                return $params['model'] === 'fallback-model';
            })
            ->andReturn($this->mockResponse);

        // Call the handler
        $result = $this->handler->attemptFallbackModel(
            'generateSocialPost',
            123,
            'linkedin',
            ['model' => 'original-model', 'messages' => []],
            $originalException
        );

        // Verify the result
        $this->assertEquals($expectedContent, $result);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_null_when_no_fallback_model_configured()
    {
        // Unset the fallback model
        config(['services.prism.fallback_model' => null]);

        // Original exception
        $originalException = new Exception('Original error');

        // The prism mock should not be called
        $this->mockPrism->shouldNotReceive('run');

        // Call the handler
        $result = $this->handler->attemptFallbackModel(
            'generateSocialPost',
            123,
            'linkedin',
            ['model' => 'original-model', 'messages' => []],
            $originalException
        );

        // Verify the result is null
        $this->assertNull($result);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_exceptions_during_fallback_attempt()
    {
        // Original exception
        $originalException = new Exception('Original error');

        // Setup prism mock to throw an exception
        $this->mockPrism->shouldReceive('run')
            ->once()
            ->andThrow(new Exception('Fallback error'));

        // Setup exception handler mock
        $this->mockExceptionHandler->shouldReceive('handleException')
            ->once()
            ->withArgs(function ($exception) {
                return $exception instanceof AIGenerationException &&
                       $exception->getOperation() === 'generateSocialPost' &&
                       $exception->getEntityId() === 123 &&
                       $exception->getPlatform() === 'linkedin' &&
                       $exception->isFallbackAttempt() === true;
            });

        // Call the handler
        $result = $this->handler->attemptFallbackModel(
            'generateSocialPost',
            123,
            'linkedin',
            ['model' => 'original-model', 'messages' => []],
            $originalException
        );

        // Verify the result is null
        $this->assertNull($result);
    }
}
