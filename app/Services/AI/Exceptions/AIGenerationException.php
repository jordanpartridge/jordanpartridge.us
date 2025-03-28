<?php

namespace App\Services\AI\Exceptions;

use Exception;
use Throwable;

class AIGenerationException extends Exception
{
    /**
     * @var string
     */
    protected $operation;

    /**
     * @var mixed
     */
    protected $entityId;

    /**
     * @var string|null
     */
    protected $platform;

    /**
     * @var bool
     */
    protected $isFallbackAttempt;

    /**
     * Create a new AI generation exception instance.
     *
     * @param string $operation
     * @param mixed $entityId
     * @param string|null $platform
     * @param string $message
     * @param bool $isFallbackAttempt
     * @param Throwable|null $previous
     */
    public function __construct(
        string $operation,
        $entityId,
        ?string $platform,
        string $message,
        bool $isFallbackAttempt = false,
        Throwable $previous = null
    ) {
        $this->operation = $operation;
        $this->entityId = $entityId;
        $this->platform = $platform;
        $this->isFallbackAttempt = $isFallbackAttempt;

        // Format a consistent error message
        $formattedMessage = "AI content generation failed: {$operation}";

        if ($entityId) {
            $formattedMessage .= " (ID: {$entityId})";
        }

        if ($platform) {
            $formattedMessage .= " for {$platform}";
        }

        if ($isFallbackAttempt) {
            $formattedMessage .= " [fallback attempt]";
        }

        $formattedMessage .= ": {$message}";

        parent::__construct($formattedMessage, 0, $previous);
    }

    /**
     * Get the operation that failed.
     *
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * Get the entity ID.
     *
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Get the platform.
     *
     * @return string|null
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    /**
     * Check if this was a fallback attempt.
     *
     * @return bool
     */
    public function isFallbackAttempt(): bool
    {
        return $this->isFallbackAttempt;
    }
}
