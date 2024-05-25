<?php

namespace App\Contracts;

use Illuminate\Http\Response;

interface WebhookHandlerInterface
{
    /**
     * Handle the incoming webhook payload.
     */
    public function handle(array $payload): Response;

    /**
     * Determine if the handler should handle the incoming payload.
     */
    public function shouldHandle(array $payload): bool;
}
