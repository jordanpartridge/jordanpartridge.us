<?php

namespace App\Services;

use App\Http\Integrations\CardApi\CardApi;
use App\Http\Integrations\CardApi\Requests\CreateDeck;
use App\Http\Integrations\CardApi\Requests\DrawCard;
use App\Http\Integrations\CardApi\Requests\GetDeck;
use JsonException;
use RuntimeException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;

final readonly class CardService
{
    public function __construct(private CardApi $cardApi)
    {
    }

    public function drawCard(string $name, int $number = 1): Response
    {

        try {
            return $this->cardApi->send(new DrawCard($name, $number));
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
            return $this->cardApi->send(new GetDeck($name));
        } catch (FatalRequestException|RequestException $e) {
            throw new RuntimeException('Failed to get deck: ' . $e->getMessage());
        }
    }

    public function initializeDeck(string $name): array
    {
        try {
            $deckResponse = $this->getDeck($name);

            return match ($deckResponse->status()) {
                200     => $deckResponse->json(),
                404     => $this->createDeck($name)->json(),
                default => throw new RuntimeException('Failed to initialize deck: ' . $deckResponse->body()),
            };
        } catch (JsonException $e) {
            throw new RuntimeException('Failed to initialize deck: ' . $e->getMessage());
        }

    }
}
