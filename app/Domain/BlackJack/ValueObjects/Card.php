<?php

namespace App\Domain\BlackJack\ValueObjects;

use InvalidArgumentException;

class Card implements \JsonSerializable, \Stringable
{
    private const VALID_SUITS = ['hearts', 'diamonds', 'clubs', 'spades'];
    private const VALID_RANKS = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
    private const FACE_CARD_VALUE = 10;
    private const ACE_HIGH_VALUE = 11;
    private const ACE_LOW_VALUE = 1;

    private function __construct(
        private readonly string $suit,
        private readonly string $rank
    ) {
        $this->validateSuit($suit);
        $this->validateRank($rank);
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['suit']) || !isset($data['rank'])) {
            throw new InvalidArgumentException('Card data must contain suit and rank');
        }

        if (!is_string($data['suit']) || !is_string($data['rank'])) {
            throw new InvalidArgumentException('Card suit and rank must be strings');
        }

        return new self($data['suit'], $data['rank']);
    }

    public function suit(): string
    {
        return $this->suit;
    }

    public function rank(): string
    {
        return $this->rank;
    }

    public function value(bool $useHighAce = true): int
    {
        $rank = strtoupper($this->rank);

        if ($rank === 'A') {
            return $useHighAce ? self::ACE_HIGH_VALUE : self::ACE_LOW_VALUE;
        }

        if (in_array($rank, ['K', 'Q', 'J'])) {
            return self::FACE_CARD_VALUE;
        }

        $numericValue = (int) $rank;
        if ($numericValue < 2 || $numericValue > 10) {
            throw new InvalidArgumentException("Invalid numeric rank: {$rank}");
        }

        return $numericValue;
    }

    public function isAce(): bool
    {
        return strtoupper($this->rank) === 'A';
    }

    public function isFaceCard(): bool
    {
        return in_array(strtoupper($this->rank), ['K', 'Q', 'J']);
    }

    public function toString(): string
    {
        return $this->__toString();
    }

    public function toArray(): array
    {
        return [
            'suit'  => $this->suit,
            'rank'  => $this->rank,
            'value' => $this->value(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    private function validateSuit(string $suit): void
    {
        if (!in_array(strtolower($suit), self::VALID_SUITS)) {
            throw new InvalidArgumentException("Invalid suit: {$suit}");
        }
    }

    private function validateRank(string $rank): void
    {
        if (!in_array(strtoupper($rank), self::VALID_RANKS)) {
            throw new InvalidArgumentException("Invalid rank: {$rank}");
        }
    }

    public function __toString(): string
    {
        return "{$this->rank} of {$this->suit}";
    }
}
