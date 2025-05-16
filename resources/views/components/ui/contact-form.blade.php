<?php

use App\Notifications\ContactFormSubmitted;
use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Contact;

new class () extends Component {
    public $name;
    public $email;
    public $phone;
    public $reason;
    public $budget;
    public $timeline;
    public $message;
    public $dropdownOpen = false;
    public $success = false;

    protected $rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|max:255',
        'phone'    => 'nullable|string|max:20',
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
        $this->validate();

        $data = [
            'name'     => $this->name,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'reason'   => $this->reason,
            'budget'   => $this->budget,
            'timeline' => $this->timeline,
            'message'  => $this->message,
        ];

        // Create a new contact record in the database
        Contact::create($data);

        // Send notification
        User::first()->notify(new ContactFormSubmitted($data));

        // Show success message
        $this->success = true;

        // Reset form fields
        $this->reset(['name', 'email', 'phone', 'reason', 'budget', 'timeline', 'message']);
    }

};
?>
@volt('contact-form')
<div class="w-full">
    @if ($success)
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-6 mb-8 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-green-800 dark:text-green-300">Message sent successfully!</h3>
                    <p class="mt-1 text-green-700 dark:text-green-400">Thank you for reaching out. I'll get back to you as soon as possible.</p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                <input
                    wire:model.lazy="name"
                    id="name"
                    type="text"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200"
                    placeholder="Your name"
                    required
                >
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                <input
                    wire:model.lazy="email"
                    id="email"
                    type="email"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200"
                    placeholder="Your email address"
                    required
                >
                @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Phone Field -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone (optional)</label>
                <input
                    wire:model.lazy="phone"
                    id="phone"
                    type="text"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200"
                    placeholder="Your phone number"
                >
                @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Budget Field -->
            <div>
                <label for="budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Budget (optional)</label>
                <input
                    wire:model.lazy="budget"
                    id="budget"
                    type="text"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200"
                    placeholder="Estimated budget"
                >
                @error('budget') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Timeline Field -->
            <div>
                <label for="timeline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Timeline (optional)</label>
                <input
                    wire:model.lazy="timeline"
                    id="timeline"
                    type="text"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200"
                    placeholder="Preferred timeline"
                >
                @error('timeline') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Reason/Project type dropdown -->
        <div class="relative">
            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Project Type *</label>
            <div class="relative">
                <button
                    type="button"
                    wire:click="toggleDropdown"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 text-left flex justify-between items-center"
                >
                    <span>{{ $reason ?? 'Select a project type' }}</span>
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div
                    x-show="$wire.dropdownOpen"
                    @click.away="$wire.dropdownOpen = false"
                    class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 rounded-md shadow-lg border border-gray-300 dark:border-gray-600 py-1"
                    x-cloak
                >
                    <div wire:click="setReason('freelance')" class="px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">Freelance Project</div>
                    <div wire:click="setReason('teaching')" class="px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">Teaching Opportunity</div>
                    <div wire:click="setReason('collaboration')" class="px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">Collaboration</div>
                    <div wire:click="setReason('other')" class="px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">Other</div>
                </div>
            </div>
            @error('reason') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Message Field -->
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message *</label>
            <textarea
                wire:model.lazy="message"
                id="message"
                rows="5"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 resize-none"
                placeholder="Tell me about your project or inquiry"
                required
            ></textarea>
            @error('message') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center mt-8">
            <button
                type="submit"
                class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 w-full md:w-auto md:px-10"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75 cursor-wait"
            >
                <span wire:loading.remove>Send Message</span>
                <span wire:loading>
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                </span>
            </button>
        </div>
    </form>
</div>
@endvolt