<?php

namespace App\Services\AI\Handlers;

use App\Services\AI\Exceptions\AIGenerationException;
use Exception;
use Prism\Prism;

class FallbackHandler
{
    /**
     * @var Prism
     */
    protected $prism;

    /**
     * @var ExceptionHandler
     */
    protected $exceptionHandler;

    /**
     * Create a new fallback handler instance.
     *
     * @param Prism $prism
     * @param ExceptionHandler $exceptionHandler
     */
    public function __construct(Prism $prism, ExceptionHandler $exceptionHandler)
    {
        $this->prism = $prism;
        $this->exceptionHandler = $exceptionHandler;
    }

    /**
     * Attempt to use the fallback model.
     *
     * @param string $operation
     * @param mixed $entityId
     * @param string|null $platform
     * @param array $params
     * @param Exception $originalException
     * @return string|null
     */
    public function attemptFallbackModel(
        string $operation,
        $entityId,
        ?string $platform,
        array $params,
        Exception $originalException
    ): ?string {
        $fallbackModel = config('services.prism.fallback_model');

        // If no fallback model is configured, return null
        if (!$fallbackModel) {
            return null;
        }

        try {
            // Update the model parameter with the fallback model
            $params['model'] = $fallbackModel;

            // Run the request with the fallback model
            $response = $this->prism->run($params);

            // Return the content if successful
            return $response->content();
        } catch (Exception $fallbackException) {
            // Create an AIGenerationException for the fallback attempt
            $exception = new AIGenerationException(
                $operation,
                $entityId,
                $platform,
                $fallbackException->getMessage(),
                true,
                $fallbackException
            );

            // Handle the exception
            $this->exceptionHandler->handleException($exception);

            // Return null to indicate the fallback attempt failed
            return null;
        }
    }
}
