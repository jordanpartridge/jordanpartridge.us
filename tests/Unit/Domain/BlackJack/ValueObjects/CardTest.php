<?php

namespace Tests\Unit\Domain\BlackJack\ValueObjects;

use App\Domain\BlackJack\ValueObjects\Card;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_card_from_array(): void
    {
        $card = Card::fromArray([
            'suit' => 'hearts',
            'rank' => 'A'
        ]);

        $this->assertEquals('hearts', $card->suit());
        $this->assertEquals('A', $card->rank());
        $this->assertEquals(11, $card->value());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_validates_suit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid suit: invalid');

        Card::fromArray([
            'suit' => 'invalid',
            'rank' => 'A'
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_validates_rank(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid rank: 11');

        Card::fromArray([
            'suit' => 'hearts',
            'rank' => '11'
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_validates_data_types(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Card suit and rank must be strings');

        Card::fromArray([
            'suit' => 123,
            'rank' => 'A'
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_calculates_face_card_values_correctly(): void
    {
        $jack = Card::fromArray(['suit' => 'hearts', 'rank' => 'J']);
        $queen = Card::fromArray(['suit' => 'diamonds', 'rank' => 'Q']);
        $king = Card::fromArray(['suit' => 'clubs', 'rank' => 'K']);

        $this->assertEquals(10, $jack->value());
        $this->assertEquals(10, $queen->value());
        $this->assertEquals(10, $king->value());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_calculates_numeric_card_values_correctly(): void
    {
        for ($i = 2; $i <= 10; $i++) {
            $card = Card::fromArray(['suit' => 'hearts', 'rank' => (string) $i]);
            $this->assertEquals($i, $card->value(), "Card with rank {$i} should have value {$i}");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_ace_values_correctly(): void
    {
        $ace = Card::fromArray(['suit' => 'hearts', 'rank' => 'A']);

        $this->assertEquals(11, $ace->value(true), 'Ace should be 11 when high');
        $this->assertEquals(1, $ace->value(false), 'Ace should be 1 when low');
        $this->assertTrue($ace->isAce(), 'isAce() should return true for Ace');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_identifies_face_cards_correctly(): void
    {
        $faceCards = ['J', 'Q', 'K'];
        $nonFaceCards = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'A'];

        foreach ($faceCards as $rank) {
            $card = Card::fromArray(['suit' => 'hearts', 'rank' => $rank]);
            $this->assertTrue($card->isFaceCard(), "Card with rank {$rank} should be identified as face card");
        }

        foreach ($nonFaceCards as $rank) {
            $card = Card::fromArray(['suit' => 'hearts', 'rank' => $rank]);
            $this->assertFalse($card->isFaceCard(), "Card with rank {$rank} should not be identified as face card");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_converts_to_string(): void
    {
        $card = Card::fromArray([
            'suit' => 'hearts',
            'rank' => 'K'
        ]);

        $this->assertEquals('K of hearts', $card->toString());
        $this->assertEquals('K of hearts', (string) $card);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_converts_to_array_and_json(): void
    {
        $card = Card::fromArray([
            'suit' => 'hearts',
            'rank' => 'K'
        ]);

        $expected = [
            'suit'  => 'hearts',
            'rank'  => 'K',
            'value' => 10
        ];

        $this->assertEquals($expected, $card->toArray());
        $this->assertEquals($expected, $card->jsonSerialize());
        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($card));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_validates_numeric_rank_bounds(): void
    {
        $card = Card::fromArray(['suit' => 'hearts', 'rank' => '2']);
        $this->assertEquals(2, $card->value(), 'Should accept minimum valid numeric rank');

        $card = Card::fromArray(['suit' => 'hearts', 'rank' => '10']);
        $this->assertEquals(10, $card->value(), 'Should accept maximum valid numeric rank');
    }
}
