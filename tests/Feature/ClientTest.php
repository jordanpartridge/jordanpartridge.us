<?php

use App\Enums\ClientStatus;
use App\Models\Client;

it('creates_client_using_factory', function () {
    $client = Client::factory()->create();

    $this->assertDatabaseCount('clients', 1);

    $this->assertDatabaseHas('clients', [
        'id'      => $client->id,
        'name'    => $client->name,
        'company' => $client->company,
        'email'   => $client->email,
        'phone'   => $client->phone,
        'website' => $client->website,
        'notes'   => $client->notes,
        'status'  => $client->status->value,
    ]);
});

it('creates_client_with_specific_attributes', function () {
    $attributes = [
        'name'    => 'John Doe',
        'company' => 'Acme Inc',
        'email'   => 'john@example.com',
        'phone'   => '555-123-4567',
        'website' => 'https://example.com',
        'notes'   => 'Important client',
        'status'  => ClientStatus::ACTIVE->value,
    ];

    $client = Client::factory()->create($attributes);

    $this->assertDatabaseHas('clients', $attributes);
    $this->assertEquals('John Doe', $client->name);
    $this->assertEquals('Acme Inc', $client->company);
    $this->assertEquals('john@example.com', $client->email);
    $this->assertEquals(ClientStatus::ACTIVE, $client->status);
});
