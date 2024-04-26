<?php

use App\Models\Ride;

it('can be created with factory', function () {
    $ride = Ride::factory()->create();
    $this->expect($ride->id)->toBeInt();
    $this->expect($ride->name)->toBeString();
    $this->expect($ride->distance)->toBeFloat();
    $this->assertDatabaseHas('rides', ['id' => $ride->id]);
});
