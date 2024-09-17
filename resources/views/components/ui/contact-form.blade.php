<?php

use App\Notifications\ContactFormSubmitted;
use Livewire\Volt\Component;
use App\Models\User;

new class () extends Component {
    public $name;
    public $email;
    public $reason;
    public $budget;
    public $timeline;
    public $message;
    public $dropdownOpen = false;

    protected $rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|max:255',
        'reason'   => 'required|string',
        'budget'   => 'nullable|string|max:255',
        'timeline' => 'nullable|string|max:255',
        'message'  => 'required|string|max:5000',
    ];

    public function toggleDropdown()
    {
        $this->dropdownOpen = !$this->dropdownOpen;
    }

    public function setReason($reason)
    {
        $reasonLabels = [
            'freelance'     => 'Freelance Project',
            'teaching'      => 'Teaching Opportunity',
            'collaboration' => 'Collaboration',
            'other'         => 'Other',
        ];

        $this->reason = $reasonLabels[$reason] ?? $reason;
        $this->toggleDropdown();
    }

    public function submit()
    {

        $data = [
            'name'     => $this->name,
            'email'    => $this->email,
            'reason'   => $this->reason,
            'budget'   => $this->budget,
            'timeline' => $this->timeline,
            'message'  => $this->message,
        ];

        User::first()->first()->notify(new ContactFormSubmitted($data));

        session()->flash('message', 'Form successfully submitted.');

        // Reset form fields
        $this->reset();
    }

}
?>
@volt('contact-form')


<div
    class="w-full max-w-2xl mx-auto mt-10 p-8 rounded-lg shadow-xl space-y-8 transition-colors duration-500 ease-in-out bg-transparent">

    @if (session()->has('message'))
        <div class="bg-green-100 border-t border-b border-green-500 text-green-700 px-4 py-3" role="alert">
            <p class="font-bold">Success</p>
            <p class="text-sm">{{ session('message') }}</p>
        </div>
    @endif

    <!-- Description Section -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 transition-colors duration-500 ease-in-out">Let's
            Connect!</h2>
        <p class="text-gray-600 dark:text-gray-300 mt-2 transition-colors duration-500 ease-in-out">I'm looking to
            collaborate on various projects. This form can help us connect and discuss opportunities for freelance
            projects, teaching, collaborations, and more.</p>
    </div>

    <!-- Fun Layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="w-full relative">
            <input wire:model="name" name="name"
                   class="border-b-2 bg-transparent w-full py-2 px-3 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 transition duration-300 ease-in-out transform hover:scale-105 border-teal-500 dark:border-pink-500 focus:ring-teal-500 dark:focus:ring-pink-500"
                   id="name" type="text" placeholder="Your Name">
            <span
                class="absolute left-0 bottom-1 w-full h-0.5 bg-teal-500 dark:bg-pink-500 transform transition-transform duration-300 ease-in-out scale-x-0 origin-right hover:origin-left hover:scale-x-100"></span>
        </div>
        <div class="w-full relative">
            <input wire:model="email" name="email"
                   class="border-b-2 bg-transparent w-full py-2 px-3 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 transition duration-300 ease-in-out transform hover:scale-105 border-teal-500 dark:border-pink-500 focus:ring-teal-500 dark:focus:ring-pink-500"
                   id="email" type="email" placeholder="Your Email">
            <span
                class="absolute left-0 bottom-1 w-full h-0.5 bg-teal-500 dark:bg-pink-500 transform transition-transform duration-300 ease-in-out scale-x-0 origin-right hover:origin-left hover:scale-x-100"></span>
        </div>
    </div>

    <!-- Custom Dropdown -->
    <div class="w-full relative">
        <div class="relative">
            <button wire:click="toggleDropdown"
                    class="border-b-2 bg-transparent w-full py-2 px-3 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 transition duration-300 ease-in-out transform hover:scale-105 border-teal-500 dark:border-pink-500 focus:ring-teal-500 dark:focus:ring-pink-500"
                    type="button">
                <span>{{ $reason ?? 'How can I help you?' }}</span>
                <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none" width="24"
                     height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
            <div
                class="@if (!$dropdownOpen) hidden @endif absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg">
                <div class="py-1">
                    <div wire:click="setReason('freelance')"
                         class="dropdown-option hover:bg-teal-100 dark:hover:bg-pink-700 hover:opacity-80 dark:hover:opacity-80 hover:scale-102 dark:hover:scale-102 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer transition-all duration-200 ease-in-out">
                        Freelance Project
                    </div>
                    <div wire:click="setReason('teaching')"
                         class="dropdown-option hover:bg-teal-100 dark:hover:bg-pink-700 hover:opacity-80 dark:hover:opacity-80 hover:scale-102 dark:hover:scale-102 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer transition-all duration-200 ease-in-out">
                        Teaching Opportunity
                    </div>
                    <div wire:click="setReason('collaboration')"
                         class="dropdown-option hover:bg-teal-100 dark:hover:bg-pink-700 hover:opacity-80 dark:hover:opacity-80 hover:scale-102 dark:hover:scale-102 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer transition-all duration-200 ease-in-out">
                        Collaboration
                    </div>
                    <div wire:click="setReason('other')"
                         class="dropdown-option hover:bg-teal-100 dark:hover:bg-pink-700 hover:opacity-80 dark:hover:opacity-80 hover:scale-102 dark:hover:scale-102 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer transition-all duration-200 ease-in-out">
                        Other
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="reason" wire:model="reason">
        <span
            class="absolute left-0 bottom-1 w-full h-0.5 bg-teal-500 dark:bg-pink-500 transform transition-transform duration-300 ease-in-out scale-x-0 origin-right hover:origin-left hover:scale-x-100"></span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="w-full relative">
            <input wire:model="budget" name="budget"
                   class="border-b-2 bg-transparent w-full py-2 px-3 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 transition duration-300 ease-in-out transform hover:scale-105 border-teal-500 dark:border-pink-500 focus:ring-teal-500 dark:focus:ring-pink-500"
                   id="budget" type="text" placeholder="Your Budget (if applicable)">
            <span
                class="absolute left-0 bottom-1 w-full h-0.5 bg-teal-500 dark:bg-pink-500 transform transition-transform duration-300 ease-in-out scale-x-0 origin-right hover:origin-left hover:scale-x-100"></span>
        </div>
        <div class="w-full relative">
            <input wire:model="timeline" name="timeline"
                   class="border-b-2 bg-transparent w-full py-2 px-3 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 transition duration-300 ease-in-out transform hover:scale-105 border-teal-500 dark:border-pink-500 focus:ring-teal-500 dark:focus:ring-pink-500"
                   id="timeline" type="text" placeholder="Your Preferred Timeline">
            <span
                class="absolute left-0 bottom-1 w-full h-0.5 bg-teal-500 dark:bg-pink-500 transform transition-transform duration-300 ease-in-out scale-x-0 origin-right hover:origin-left hover:scale-x-100"></span>
        </div>
    </div>
    <div class="w-full relative">
        <textarea wire:model="message" name="message"
                  class="border-b-2 bg-transparent w-full py-2 px-3 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 transition duration-300 ease-in-out transform hover:scale-105 border-teal-500 dark:border-pink-500 focus:ring-teal-500 dark:focus:ring-pink-500"
                  id="message" placeholder="Your Message" rows="4"></textarea>
        <span
            class="absolute left-0 bottom-1 w-full h-0.5 bg-teal-500 dark:bg-pink-500 transform transition-transform duration-300 ease-in-out scale-x-0 origin-right hover:origin-left hover:scale-x-100"></span>
    </div>

    <div class="flex items-center justify-center mt-8">
        <x-button-link wire:click="submit" type="button">
            Submit
        </x-button-link>
    </div>

</div>
@endvolt
