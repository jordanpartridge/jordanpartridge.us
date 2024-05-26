<?php

namespace Database\Seeders;

use App\Models\Suit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SuitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suits = [
            [
                'name'   => 'Hearts',
                'symbol' => '️♥',
                'color'  => 'Red',
            ],
            [
                'name'   => 'Diamonds',
                'symbol' => '♦',
                'color'  => 'Red',
            ],
            [
                'name'   => 'Clubs',
                'symbol' => '♣',
                'color'  => 'Black',
            ],
            [
                'name'   => 'Spades',
                'symbol' => '♠',
                'color'  => 'Black',
            ],
        ];
        collect($suits)->each(function ($suit) {
            Log::channel('slack')->info('Creating suit: ' . $suit['name'], $suit);
            Suit::factory()->create(['name' => $suit['name'], 'symbol' => $suit['symbol'], 'color' => $suit['color']]);
        });
    }
}
