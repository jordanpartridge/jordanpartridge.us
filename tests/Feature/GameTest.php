<?php

use App\Models\Game;
use Illuminate\Support\Carbon;

it('can successfully be created with factory default settings.', function () {
    $game = Game::factory()->create();
    expect($game->deck_slug)->toBeString()
        ->and($game->name)->toBeString()
        ->and($game->created_at)->toBeInstanceOf(Carbon::class)
        ->and($game->updated_at)->toBeInstanceOf(Carbon::class)
        ->and($game->deleted_at)->toBeNull();
});

it('belongs to many players (users).', function () {
    $game = Game::factory()->create();
    $game->players()->create(['name' => 'John Doe', 'email' => 'john@example.com', 'password' => bcrypt('password')]);
    $game->players()->create(['name' => 'Jane Doe', 'email' => 'jane@example.com', 'password' => bcrypt('password')]);
    expect($game->players()->count())->toBe(2);
});
