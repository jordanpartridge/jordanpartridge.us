<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Suit;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = [
            '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A',
        ];

        // foreach suit populate the ranks
        Suit::query()->each(fn (Suit $suit) => collect($ranks)
            ->each(fn (string $rank) => Card::create([
                'suit_id' => $suit->id,
                'image'   => 'cards/' . $suit->name . '/' . $rank . '.png',


                'rank' => $rank,
            ])));
    }
}
