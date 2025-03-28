<?php

namespace App\Services\AI\Handlers;

use App\Models\FailedTask;
use App\Models\Post;
use App\Services\AI\Exceptions\AIGenerationException;
use Exception;
use Illuminate\Support\Facades\Log;

class ExceptionHandler
{
    /**
     * Record an error with a failed task entry.
     *
     * @param string $operation
     * @param mixed $entityId
     * @param string|null $platform
     * @param string $message
     * @param bool $isFallbackAttempt
     * @return void
     */
    public function recordError(
        string $operation,
        $entityId,
        ?string $platform,
        string $message,
        bool $isFallbackAttempt = false
    ): void {
        // Log error to the application log
        $context = [
            'operation'   => $operation,
            'entity_id'   => $entityId,
            'platform'    => $platform,
            'is_fallback' => $isFallbackAttempt,
        ];

        Log::error("AI Generation Error: {$message}", $context);

        // Record to failed_tasks table if available
        try {
            FailedTask::create([
                'task_type'   => "ai.{$operation}" . ($isFallbackAttempt ? '.fallback' : ''),
                'entity_type' => 'post',
                'entity_id'   => $entityId,
                'error'       => $message,
                'context'     => json_encode([
                    'platform'    => $platform,
                    'is_fallback' => $isFallbackAttempt,
                ]),
            ]);
        } catch (Exception $e) {
            // If we can't record to the database, just log it
            Log::error("Failed to record AI error in database: {$e->getMessage()}", $context);
        }
    }

    /**
     * Process an exception from AI content generation.
     *
     * @param AIGenerationException $exception
     * @return void
     */
    public function handleException(AIGenerationException $exception): void
    {
        $this->recordError(
            $exception->getOperation(),
            $exception->getEntityId(),
            $exception->getPlatform(),
            $exception->getMessage(),
            $exception->isFallbackAttempt()
        );
    }

    /**
     * Get fallback content based on the operation and entity.
     *
     * @param string $operation
     * @param mixed $entity
     * @param string|null $platform
     * @return string|array
     */
    public function getFallbackContent(string $operation, $entity, ?string $platform = null)
    {
        // If entity is a Post, extract the title and description for fallback content
        if ($entity instanceof Post) {
            $title = $entity->title;
            $description = $entity->description ?? $entity->excerpt ?? substr($entity->body ?? $entity->content ?? '', 0, 150);

            switch ($operation) {
                case 'generateSocialPost':
                    return "I'm excited to share my latest article: \"{$title}\"\n\n{$description}\n\nCheck it out on my website!";

                case 'generateSummary':
                    return "This article discusses {$title}. " . ($description ? "{$description}" : "");

                case 'generateSeoMetadata':
                    return [
                        'meta_title'       => $title,
                        'meta_description' => substr($description, 0, 160),
                        'keywords'         => array_filter(array_map('trim', explode(',', strtolower($title)))),
                    ];

                default:
                    return "Content could not be generated at this time.";
            }
        }

        // Default fallback content
        return "Content not available.";
    }
}
