<?php

namespace App\Http\Handlers;

use App\Contracts\WebhookHandlerInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class VerificationHandler implements WebhookHandlerInterface
{
    public function handle(array $payload): Response
    {
        Log::channel('slack')->info('Verification request received');

        return response(['challenge' => $payload['challenge']], 200);
    }

    /**
     * Determine if the handler should handle the incoming payload.
     */
    public function shouldHandle(array $payload): bool
    {
        return array_key_exists('token', $payload)
            && array_key_exists('challenge', $payload) &&
            $payload['token'] === config('services.slack.verification.token');
    }
}
