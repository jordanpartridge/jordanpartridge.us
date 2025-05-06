<?php

use App\Models\Client;

it('haz factory', function () {
    Client::factory()->create();
    $this->assertDatabaseCount('clients', 1);
});
