<?php

use App\Models\Game;
use Illuminate\Support\Carbon;

it('has a factory, with default in sync with the migrations', function () {
    $game = Game::factory()->create();
    expect($game->deck_slug)->toBeString()
        ->and($game->name)->toBeString()
        ->and($game->created_at)->toBeInstanceOf(Carbon::class)
        ->and($game->updated_at)->toBeInstanceOf(Carbon::class)
        ->and($game->deleted_at)->toBeNull();
});
