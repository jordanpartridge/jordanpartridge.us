<?php

use App\Models\Client;
use App\Models\ClientEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('client_email_bodies table has expected columns', function () {
    // Run the migrations
    $this->assertTrue(Schema::hasTable('client_email_bodies'));

    // Check for the expected columns
    $this->assertTrue(Schema::hasColumns('client_email_bodies', [
        'id',
        'client_email_id',
        'html_content',
        'text_content',
        'created_at',
        'updated_at',
    ]));
});

test('client_email_bodies table has correct foreign key constraint', function () {
    // Create a client and email
    $client = Client::factory()->create();
    $clientEmail = ClientEmail::factory()->create([
        'client_id' => $client->id,
    ]);

    // Insert a record into client_email_bodies using DB facade
    $bodyId = DB::table('client_email_bodies')->insertGetId([
        'client_email_id' => $clientEmail->id,
        'html_content'    => '<p>This is an HTML email.</p>',
        'text_content'    => 'This is a plain text email.',
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

    $this->assertNotNull($bodyId);

    // Test cascade delete when parent email is deleted
    $clientEmail->delete();

    $this->assertDatabaseMissing('client_email_bodies', [
        'id' => $bodyId,
    ]);
});

test('multiple formats of email content can be stored', function () {
    // Create a client and email
    $client = Client::factory()->create();
    $clientEmail = ClientEmail::factory()->create([
        'client_id' => $client->id,
    ]);

    // Create email body with both HTML and text content
    $bodyId = DB::table('client_email_bodies')->insertGetId([
        'client_email_id' => $clientEmail->id,
        'html_content'    => '<div><h1>Test Email</h1><p>This is a <strong>formatted</strong> email.</p></div>',
        'text_content'    => "Test Email\n\nThis is a formatted email.",
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

    // Verify the data was stored correctly
    $emailBody = DB::table('client_email_bodies')->find($bodyId);

    $this->assertEquals('<div><h1>Test Email</h1><p>This is a <strong>formatted</strong> email.</p></div>', $emailBody->html_content);
    $this->assertEquals("Test Email\n\nThis is a formatted email.", $emailBody->text_content);
});

test('email body content can be null', function () {
    // Create a client and email
    $client = Client::factory()->create();
    $clientEmail = ClientEmail::factory()->create([
        'client_id' => $client->id,
    ]);

    // Create email body with only HTML content
    $htmlOnlyId = DB::table('client_email_bodies')->insertGetId([
        'client_email_id' => $clientEmail->id,
        'html_content'    => '<p>HTML only email</p>',
        'text_content'    => null,
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

    // Create another with only text content
    $textOnlyId = DB::table('client_email_bodies')->insertGetId([
        'client_email_id' => $clientEmail->id,
        'html_content'    => null,
        'text_content'    => 'Text only email',
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

    // Verify the data was stored correctly
    $htmlOnly = DB::table('client_email_bodies')->find($htmlOnlyId);
    $textOnly = DB::table('client_email_bodies')->find($textOnlyId);

    $this->assertEquals('<p>HTML only email</p>', $htmlOnly->html_content);
    $this->assertNull($htmlOnly->text_content);

    $this->assertNull($textOnly->html_content);
    $this->assertEquals('Text only email', $textOnly->text_content);
});
