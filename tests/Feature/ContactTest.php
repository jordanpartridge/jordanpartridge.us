<?php

use App\Models\Contact;
use App\Models\User;
use App\Notifications\ContactFormSubmitted;
use Illuminate\Support\Facades\Notification;

it('has factory implementation', function () {
    $contact = Contact::factory()->create();

    expect($contact)->toBeInstanceOf(Contact::class);
    expect($contact->name)->toBeString();
    expect($contact->email)->toBeString();
    expect($contact->phone)->toBeString();
});

it('can store contact submissions', function () {
    $contactData = [
        'name'     => 'Test User',
        'email'    => 'test@example.com',
        'phone'    => '123-456-7890',
        'reason'   => 'Freelance Project',
        'budget'   => '$1,000 - $5,000',
        'timeline' => '1-3 months',
        'message'  => 'This is a test message',
    ];

    $contact = Contact::create($contactData);

    expect($contact->name)->toBe('Test User');
    expect($contact->email)->toBe('test@example.com');
    expect($contact->phone)->toBe('123-456-7890');
    expect($contact->reason)->toBe('Freelance Project');
    expect($contact->budget)->toBe('$1,000 - $5,000');
    expect($contact->timeline)->toBe('1-3 months');
    expect($contact->message)->toBe('This is a test message');
});

it('sends a notification when a contact is submitted', function () {
    Notification::fake();

    $user = User::factory()->create();

    $contactData = [
        'name'     => 'Test User',
        'email'    => 'test@example.com',
        'phone'    => '123-456-7890',
        'reason'   => 'Freelance Project',
        'budget'   => '$1,000 - $5,000',
        'timeline' => '1-3 months',
        'message'  => 'This is a test message',
    ];

    // Create the contact
    Contact::create($contactData);

    // Send the notification
    $user->notify(new ContactFormSubmitted($contactData));

    // Assert the notification was sent
    Notification::assertSentTo(
        $user,
        ContactFormSubmitted::class,
        function ($notification) use ($contactData) {
            return $notification->formData['name'] === $contactData['name'] &&
                $notification->formData['email'] === $contactData['email'] &&
                $notification->formData['phone'] === $contactData['phone'] &&
                $notification->formData['reason'] === $contactData['reason'] &&
                $notification->formData['budget'] === $contactData['budget'] &&
                $notification->formData['timeline'] === $contactData['timeline'] &&
                $notification->formData['message'] === $contactData['message'];
        }
    );
});
