<?php

namespace Tests\Unit\Services\AI;

use App\Services\AI\Exceptions\AIGenerationException;
use Tests\TestCase;

class AIGenerationExceptionTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_formats_message_with_required_prefix()
    {
        // Create a basic exception
        $exception = new AIGenerationException(
            'generateSocialPost',
            123,
            'linkedin',
            'Original error message'
        );

        // Check that the message has the required prefix
        $this->assertStringStartsWith(
            'AI content generation failed:',
            $exception->getMessage()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_includes_entity_id_in_formatted_message()
    {
        // Create exception with entity ID
        $exception = new AIGenerationException(
            'generateSocialPost',
            123,
            'linkedin',
            'Original error message'
        );

        // Check that the entity ID is included
        $this->assertStringContainsString(
            '(ID: 123)',
            $exception->getMessage()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_includes_platform_in_formatted_message()
    {
        // Create exception with platform
        $exception = new AIGenerationException(
            'generateSocialPost',
            123,
            'linkedin',
            'Original error message'
        );

        // Check that the platform is included
        $this->assertStringContainsString(
            'for linkedin',
            $exception->getMessage()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_includes_fallback_attempt_in_formatted_message()
    {
        // Create exception with fallback attempt flag
        $exception = new AIGenerationException(
            'generateSocialPost',
            123,
            'linkedin',
            'Original error message',
            true
        );

        // Check that the fallback attempt is indicated
        $this->assertStringContainsString(
            '[fallback attempt]',
            $exception->getMessage()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_includes_original_error_message()
    {
        // Original error message
        $originalMessage = 'Original error message';

        // Create exception
        $exception = new AIGenerationException(
            'generateSocialPost',
            123,
            'linkedin',
            $originalMessage
        );

        // Check that the original message is included
        $this->assertStringContainsString(
            $originalMessage,
            $exception->getMessage()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_provides_accessors_for_all_properties()
    {
        // Create exception with all properties
        $exception = new AIGenerationException(
            'generateSocialPost',
            123,
            'linkedin',
            'Original error message',
            true
        );

        // Check accessors
        $this->assertEquals('generateSocialPost', $exception->getOperation());
        $this->assertEquals(123, $exception->getEntityId());
        $this->assertEquals('linkedin', $exception->getPlatform());
        $this->assertTrue($exception->isFallbackAttempt());
    }
}
