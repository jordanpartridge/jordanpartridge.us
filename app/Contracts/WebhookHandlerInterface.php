<?php

namespace App\Contracts;

use Illuminate\Http\Response;

interface WebhookHandlerInterface
{
    /**
     * Handle the incoming webhook payload.
     *
     * @param array $payload
     * @return Response
     */
    public function handle(array $payload): Response;

    /**
     * Determine if the handler should handle the incoming payload.
     *
     * @param array $payload
     * @return bool
     */
    public function shouldHandle(array $payload): bool;
}
