<?php

namespace Tests\Unit\Services\AI;

use App\Services\AI\AIContentService;
use App\Services\AI\Handlers\ExceptionHandler;
use App\Services\AI\Handlers\FallbackHandler;
use Prism\Prism;

// Test subclass to ensure consistent test results
class TestableAIContentService extends AIContentService
{
    /**
     * Create a new testable AI content service instance.
     *
     * @param Prism $prism
     */
    public function __construct(
        Prism $prism
    ) {
        // Create handlers
        $exceptionHandler = new ExceptionHandler();
        $fallbackHandler = new FallbackHandler($prism, $exceptionHandler);

        // Call parent constructor with all dependencies
        parent::__construct($prism, $exceptionHandler, $fallbackHandler);
    }
}
