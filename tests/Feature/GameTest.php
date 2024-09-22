<?php

use App\Models\Game;
use Illuminate\Support\Carbon;
use App\Models\User;

it('can successfully be created with factory default settings.', function () {
    $game = Game::factory()->create();
    expect($game->deck_slug)->toBeString()->not()->toBeEmpty()
        ->and($game->name)->toBeString()->not()->toBeEmpty()
        ->and($game->created_at)->toBeInstanceOf(Carbon::class)
        ->and($game->updated_at)->toBeInstanceOf(Carbon::class)
        ->and($game->deleted_at)->toBeNull();
});

it('belongs to many players (users).', function () {
    $game = Game::factory()->has(User::factory(2), 'players')->create();
    expect($game->players()->count())->toBe(2)
        ->and($game->players()->first())->toBeInstanceOf(User::class);
});
