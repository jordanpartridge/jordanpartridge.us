<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Add custom assertions
        TestResponse::macro('assertMiddleware', function (array $middleware) {
            Assert::assertEquals(
                array_values($middleware),
                array_intersect(
                    array_values($middleware),
                    $this->baseResponse->middlewareStack() ?? []
                ),
                'Middleware stack does not match expected middleware.'
            );

            return $this;
        });
    }
}
