<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use App\Notifications\ContactFormSubmitted;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Store a newly created contact in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'nullable|string|max:20',
            'reason'   => 'nullable|string|max:255',
            'budget'   => 'nullable|string|max:255',
            'timeline' => 'nullable|string|max:255',
            'message'  => 'required|string|max:5000',
        ]);

        $contact = Contact::create($validated);

        // Send notification
        User::first()->notify(new ContactFormSubmitted($validated));

        return redirect()->back()->with('message', 'Form successfully submitted.');
    }
}
