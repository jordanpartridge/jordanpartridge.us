<?php

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('client_emails table has expected columns', function () {
    // Run the migrations
    $this->assertTrue(Schema::hasTable('client_emails'));

    // Check for the expected columns
    $this->assertTrue(Schema::hasColumns('client_emails', [
        'id',
        'client_id',
        'gmail_message_id',
        'subject',
        'snippet',
        'from_email',
        'from_name',
        'is_sent',
        'received_at',
        'thread_id',
        'labels',
        'has_attachment',
        'created_at',
        'updated_at',
    ]));
});

test('client_emails table has correct foreign key constraint', function () {
    // Create a client
    $client = Client::factory()->create();

    // Insert a record into client_emails using DB facade
    $emailId = DB::table('client_emails')->insertGetId([
        'client_id'        => $client->id,
        'gmail_message_id' => 'msg_123456789',
        'subject'          => 'Test Email Subject',
        'from_email'       => 'test@example.com',
        'from_name'        => 'Test Sender',
        'is_sent'          => false,
        'received_at'      => now(),
        'thread_id'        => 'thread_123456789',
        'created_at'       => now(),
        'updated_at'       => now(),
    ]);

    $this->assertNotNull($emailId);

    // Test cascade delete
    $client->delete();

    $this->assertDatabaseMissing('client_emails', [
        'id' => $emailId,
    ]);
});
