<?php

namespace App\Services;

use App\Http\Integrations\CardApi\CardApi;
use App\Http\Integrations\CardApi\Requests\CreateDeck;
use App\Http\Integrations\CardApi\Requests\DrawCard;
use App\Http\Integrations\CardApi\Requests\GetDeck;
use Exception;
use RuntimeException;
use Saloon\Http\Response;

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

    public function createDeck(string $name): Response
    {
        try {
            $deck = $this->cardApi->send(new CreateDeck($name));
        } catch (Exception $e) {
            throw new RuntimeException('Failed to create deck: ' . $e->getMessage());
        }

        return $deck;
    }

    public function getDeck(string $name): Response
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
            $deck = $this->getDeck($name);
            if ($deck->status() === 404) {
                $deck = $this->createDeck($name);
            }

            return $deck->json();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to initialize deck: ' . $e->getMessage());
        }
    }
}
