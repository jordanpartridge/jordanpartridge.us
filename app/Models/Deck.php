<?php

namespace App\Models;

use Illuminate\Support\Collection;

class Deck
{
    private Collection $cards;

    public function __construct()
    {
        $this->cards = new Collection();
        $this->initialize();
    }

    public function initialize(): void
    {
        $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
        $ranks = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $this->cards->push(new Card($suit, $rank));
            }
        }
    }

    /**
     * Shuffle the deck
     */
    public function shuffle(): void
    {
        $this->cards = $this->cards->shuffle();
    }

    /**
     * Draw a card from the deck
     */
    public function draw($count = 1): Collection
    {
        return $this->cards->splice(0, $count);
    }
}
