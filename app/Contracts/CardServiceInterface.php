<?php

namespace App\Contracts;

use Saloon\Http\Response;

interface CardServiceInterface
{
    /**
     * Draw cards from a deck
     *
     * @param string $name The deck name
     * @param int $number Number of cards to draw
     * @return Response
     */
    public function drawCard(string $name, int $number = 1): Response;

    /**
     * Create a new deck
     *
     * @param string $name The deck name
     * @return Response
     */
    public function createDeck(string $name): Response;

    /**
     * Get an existing deck
     *
     * @param string $name The deck name
     * @return Response
     */
    public function getDeck(string $name): Response;

    /**
     * Initialize a deck (get existing or create new)
     *
     * @param string $name The deck name
     * @return array
     */
    public function initializeDeck(string $name): array;
}
