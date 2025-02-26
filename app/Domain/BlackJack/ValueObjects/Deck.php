<?php

namespace App\Domain\BlackJack\ValueObjects;

use InvalidArgumentException;

class Deck
{
    /** @var Card[] */
    private array $cards;

    private function __construct(
        private readonly string $slug,
        array $cards = []
    ) {
        $this->cards = $cards;
    }

    public static function create(string $slug): self
    {
        return new self($slug);
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['slug'])) {
            throw new InvalidArgumentException('Deck data must contain a slug');
        }

        $cards = [];
        if (isset($data['cards']) && is_array($data['cards'])) {
            foreach ($data['cards'] as $cardData) {
                $cards[] = Card::fromArray($cardData);
            }
        }

        return new self($data['slug'], $cards);
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function drawCard(): ?Card
    {
        if (empty($this->cards)) {
            return null;
        }

        return array_shift($this->cards);
    }

    public function drawCards(int $count): array
    {
        $drawnCards = [];
        for ($i = 0; $i < $count; $i++) {
            $card = $this->drawCard();
            if ($card === null) {
                break;
            }
            $drawnCards[] = $card;
        }

        return $drawnCards;
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function isEmpty(): bool
    {
        return empty($this->cards);
    }

    public function count(): int
    {
        return count($this->cards);
    }

    public function toArray(): array
    {
        return [
            'slug'      => $this->slug,
            'cards'     => array_map(fn (Card $card) => $card->toArray(), $this->cards),
            'remaining' => $this->count(),
        ];
    }
}
