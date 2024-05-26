<?php

use App\Models\Card;

it('can be created with factory', function () {
    $card = Card::factory()->create();
    expect($card->id)->toBeInt();
    expect($card->suit_id)->toBeInt();
    expect($card->rank)->toBeString();
});
