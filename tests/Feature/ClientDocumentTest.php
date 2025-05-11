<?php

use App\Models\Client;
use App\Models\User;
use App\Models\ClientDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('it can upload a document for a client', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    $file = UploadedFile::fake()->create('test-document.pdf', 100, 'application/pdf');

    $this->actingAs($user);

    // Store the file path like the dashboard would
    $path = Storage::disk('local')->putFile('client-documents', $file);

    $document = $client->documents()->create([
        'uploaded_by'       => $user->id,
        'filename'          => $path,
        'original_filename' => $file->getClientOriginalName(),
        'mime_type'         => $file->getClientMimeType(),
        'file_size'         => $file->getSize(),
        'description'       => 'Test document description',
    ]);

    expect($document)->toBeInstanceOf(ClientDocument::class);
    expect($document->client_id)->toBe($client->id);
    expect($document->uploaded_by)->toBe($user->id);
    expect($document->original_filename)->toBe('test-document.pdf');
    expect($document->description)->toBe('Test document description');
    expect($document->file_size_for_humans)->toContain('KB');

    Storage::disk('local')->assertExists($path);
});

test('it can retrieve documents for a client', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    ClientDocument::factory()->count(3)->create([
        'client_id'   => $client->id,
        'uploaded_by' => $user->id,
    ]);

    expect($client->documents()->count())->toBe(3);
    expect($client->documents()->latest()->first())->toBeInstanceOf(ClientDocument::class);
});

test('document file size is human readable', function () {
    $document = ClientDocument::factory()->create(['file_size' => 1048576]); // 1MB

    expect($document->file_size_for_humans)->toBe('1 MB');
});
