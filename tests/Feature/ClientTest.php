<?php

use App\Enums\ClientStatus;
use App\Models\Client;
use App\Models\User;

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

it('retrieves_client_by_id', function () {
    $client = Client::factory()->create([
        'name' => 'Jane Smith',
    ]);

    $retrievedClient = Client::find($client->id);

    $this->assertInstanceOf(Client::class, $retrievedClient);
    $this->assertEquals('Jane Smith', $retrievedClient->name);
    $this->assertEquals($client->email, $retrievedClient->email);
});

it('updates_client_information', function () {
    $client = Client::factory()->create();

    $client->update([
        'name'   => 'Updated Name',
        'email'  => 'updated@example.com',
        'status' => ClientStatus::FORMER->value,
    ]);

    $this->assertDatabaseHas('clients', [
        'id'     => $client->id,
        'name'   => 'Updated Name',
        'email'  => 'updated@example.com',
        'status' => ClientStatus::FORMER->value,
    ]);

    $updatedClient = Client::find($client->id);
    $this->assertEquals('Updated Name', $updatedClient->name);
    $this->assertEquals(ClientStatus::FORMER, $updatedClient->status);
});

it('deletes_client', function () {
    $client = Client::factory()->create();
    $clientId = $client->id;

    $this->assertDatabaseHas('clients', ['id' => $clientId]);

    $client->delete();

    $this->assertDatabaseMissing('clients', ['id' => $clientId]);
    $this->assertNull(Client::find($clientId));
});

it('filters_clients_by_status', function () {
    // Create clients with different statuses
    Client::factory()->create(['status' => ClientStatus::LEAD->value]);
    Client::factory()->create(['status' => ClientStatus::LEAD->value]);
    Client::factory()->create(['status' => ClientStatus::ACTIVE->value]);
    Client::factory()->create(['status' => ClientStatus::FORMER->value]);

    // Count clients by status
    $leadClients = Client::where('status', ClientStatus::LEAD->value)->get();
    $activeClients = Client::where('status', ClientStatus::ACTIVE->value)->get();
    $formerClients = Client::where('status', ClientStatus::FORMER->value)->get();

    $this->assertCount(2, $leadClients);
    $this->assertCount(1, $activeClients);
    $this->assertCount(1, $formerClients);

    // Verify that the clients have the correct status
    foreach ($leadClients as $client) {
        $this->assertEquals(ClientStatus::LEAD, $client->status);
    }

    foreach ($activeClients as $client) {
        $this->assertEquals(ClientStatus::ACTIVE, $client->status);
    }

    foreach ($formerClients as $client) {
        $this->assertEquals(ClientStatus::FORMER, $client->status);
    }
});

it('associates_client_with_user', function () {
    $user = User::factory()->create();
    $client = Client::factory()->assignedToUser($user)->create();

    $this->assertEquals($user->id, $client->user_id);
    $this->assertInstanceOf(User::class, $client->user);
    $this->assertTrue($client->user->is($user));

    // Test the inverse relationship
    $this->assertCount(1, $user->clients);
    $this->assertTrue($user->clients->contains($client));
});

it('fetches_clients_for_user', function () {
    $user = User::factory()->create();

    // Create 3 clients for the user
    Client::factory()->count(3)->assignedToUser($user)->create();

    // Create 2 unassigned clients
    Client::factory()->count(2)->create();

    $this->assertCount(3, $user->clients);
    $this->assertCount(5, Client::all());
});
