<?php

use App\Models\Client;
use App\Models\ClientEmail;

it('creates_client_email_using_factory', function () {
    $clientEmail = ClientEmail::factory()->create();

    $this->assertDatabaseCount('client_emails', 1);

    $this->assertDatabaseHas('client_emails', [
        'id'               => $clientEmail->id,
        'gmail_message_id' => $clientEmail->gmail_message_id,
        'subject'          => $clientEmail->subject,
        'from'             => $clientEmail->from,
    ]);
});

it('creates_client_email_with_specific_attributes', function () {
    $client = Client::factory()->create();

    $attributes = [
        'client_id'        => $client->id,
        'gmail_message_id' => 'msg_12345abcde',
        'subject'          => 'Test Email Subject',
        'from'             => 'sender@example.com',
        'to'               => ['recipient@example.com'],
        'cc'               => ['cc@example.com'],
        'bcc'              => ['bcc@example.com'],
        'snippet'          => 'This is an email preview...',
        'body'             => '<p>This is the full email body content.</p>',
        'label_ids'        => ['INBOX', 'IMPORTANT'],
        'email_date'       => now()->subHours(3),
    ];

    $clientEmail = ClientEmail::factory()->create($attributes);

    // Test basic attributes
    $this->assertEquals($client->id, $clientEmail->client_id);
    $this->assertEquals('msg_12345abcde', $clientEmail->gmail_message_id);
    $this->assertEquals('Test Email Subject', $clientEmail->subject);
    $this->assertEquals('sender@example.com', $clientEmail->from);

    // Test array attributes
    $this->assertIsArray($clientEmail->to);
    $this->assertContains('recipient@example.com', $clientEmail->to);

    $this->assertIsArray($clientEmail->cc);
    $this->assertContains('cc@example.com', $clientEmail->cc);

    $this->assertIsArray($clientEmail->bcc);
    $this->assertContains('bcc@example.com', $clientEmail->bcc);

    $this->assertIsArray($clientEmail->label_ids);
    $this->assertContains('INBOX', $clientEmail->label_ids);
    $this->assertContains('IMPORTANT', $clientEmail->label_ids);
});

it('associates_email_with_client', function () {
    $client = Client::factory()->create();
    $clientEmail = ClientEmail::factory()->create([
        'client_id' => $client->id,
    ]);

    $this->assertEquals($client->id, $clientEmail->client_id);
    $this->assertInstanceOf(Client::class, $clientEmail->client);
    $this->assertTrue($clientEmail->client->is($client));
});

it('updates_client_email_information', function () {
    $clientEmail = ClientEmail::factory()->create();

    $clientEmail->update([
        'subject'   => 'Updated Subject',
        'snippet'   => 'Updated preview text',
        'label_ids' => ['UPDATED', 'TAGS'],
    ]);

    $this->assertDatabaseHas('client_emails', [
        'id'      => $clientEmail->id,
        'subject' => 'Updated Subject',
        'snippet' => 'Updated preview text',
    ]);

    $updatedEmail = ClientEmail::find($clientEmail->id);
    $this->assertEquals('Updated Subject', $updatedEmail->subject);
    $this->assertEquals(['UPDATED', 'TAGS'], $updatedEmail->label_ids);
});

it('deletes_client_email', function () {
    $clientEmail = ClientEmail::factory()->create();
    $emailId = $clientEmail->id;

    $this->assertDatabaseHas('client_emails', ['id' => $emailId]);

    $clientEmail->delete();

    $this->assertDatabaseMissing('client_emails', ['id' => $emailId]);
    $this->assertNull(ClientEmail::find($emailId));
});

it('serializes_array_fields_as_json', function () {
    $clientEmail = ClientEmail::factory()->create([
        'to'        => ['primary@example.com', 'secondary@example.com'],
        'cc'        => ['cc1@example.com', 'cc2@example.com'],
        'label_ids' => ['INBOX', 'CATEGORY_PERSONAL', 'IMPORTANT'],
    ]);

    // Reload from database to ensure json serialization worked
    $clientEmail = ClientEmail::find($clientEmail->id);

    $this->assertIsArray($clientEmail->to);
    $this->assertCount(2, $clientEmail->to);
    $this->assertContains('primary@example.com', $clientEmail->to);
    $this->assertContains('secondary@example.com', $clientEmail->to);

    $this->assertIsArray($clientEmail->cc);
    $this->assertCount(2, $clientEmail->cc);

    $this->assertIsArray($clientEmail->label_ids);
    $this->assertCount(3, $clientEmail->label_ids);
    $this->assertContains('CATEGORY_PERSONAL', $clientEmail->label_ids);
});
