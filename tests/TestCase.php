<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // In tests, we don't want Laravel's default cache headers to interfere
        // with our assertion of specific cache headers
        if (app()->environment('testing')) {
            // Disable default cache headers during tests to allow our middleware to set them
            config(['session.expire_on_close' => false]);

            // Use the array cache driver during tests
            config(['cache.default' => 'array']);

            // Set some configuration options specifically for testing
            config(['session.driver' => 'array']);
        }
    }
}
