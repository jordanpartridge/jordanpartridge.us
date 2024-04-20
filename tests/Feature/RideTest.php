<?php

use App\Models\Ride;

test('can be created with factory', function () {
    $ride = Ride::factory()->create();
    expect($ride->id)->toBeInt();
    expect($ride->name)->toBeString();
    expect($ride->distance)->toBeFloat();
});
