<?php

namespace App\Services;

use App\Http\Integrations\CardApi\CardApi;
use App\Http\Integrations\CardApi\Requests\CreateDeck;
use App\Http\Integrations\CardApi\Requests\DrawCard;
use App\Http\Integrations\CardApi\Requests\GetDeck;
use RuntimeException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
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
            return $this->cardApi->send(new CreateDeck($name));

        } catch (FatalRequestException|RequestException $e) {
            throw new RuntimeException('Failed to create deck: ' . $e->getMessage());
        }
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
        $deckResponse = $this->getDeck($name);
        switch ($deckResponse->status()) {
            case 200:
                return $deckResponse->json();
            case 404:
                return $this->createDeck($name)->json();
            default:
                throw new RuntimeException('Failed to initialize deck: ' . $deckResponse->body());
        }

    }
}
