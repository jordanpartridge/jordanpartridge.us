<?php

namespace App\Services;

use App\Http\Integrations\CardApi\CardApi;
use App\Http\Integrations\CardApi\Requests\CreateDeck;
use App\Http\Integrations\CardApi\Requests\DrawCard;
use App\Http\Integrations\CardApi\Requests\GetDeck;
use Exception;
use RuntimeException;

final readonly class CardService
{
    public function __construct(private CardApi $cardApi)
    {
    }

    public function drawCard(string $name): Response
    {

        try {
            return $this->cardApi->send(new DrawCard($name));
        } catch (FatalRequestException|RequestException $e) {
            throw new RuntimeException('Failed to draw card: ' . $e->getMessage());
        }
    }

    public function createDeck(string $name): array
    {
        try {
            $deck = $this->cardApi->send(new CreateDeck($name))->json();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to create deck: ' . $e->getMessage());
        }
        return $deck;
    }

    public function getDeck($name = null): array
    {
        try {
            return $this->cardApi->send(new CreateDeck($name));

        } catch (FatalRequestException|RequestException $e) {
            throw new RuntimeException('Failed to create deck: ' . $e->getMessage());
        }
    }

    public function initializeDeck(string $name): array
    {
        try {
            $existingDeck = $this->getDeck($name);
            if (isset($existingDeck['message']) && $existingDeck['message'] === 'Not Found.') {
                return $this->createDeck($name);
            }

            return $existingDeck;
        } catch (Exception $e) {
            throw new RuntimeException('Failed to initialize deck: ' . $e->getMessage());
        }
    }
}
