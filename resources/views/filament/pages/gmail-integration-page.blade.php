<x-filament::page>
    @if ($this->isAuthenticated())
        <!-- Authenticated State - Clean and Functional -->
        <x-filament::section>
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300 me-4">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Connected to Gmail
                    </div>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $this->getAuthStatusMessage() }}
                </div>
            </div>

            <!-- Recent Messages Preview -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium dark:text-gray-200">Recent Messages</h3>
                        <a href="{{ route('filament.admin.pages.gmail-messages-page') }}"
                           class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium">
                            View All →
                        </a>
                    </div>

                    @php
                        $messages = $this->getMessages();
                    @endphp

                    @if (count($messages))
                        <ul class="space-y-3">
                            @foreach ($messages->take(3) as $message)
                                <li class="p-4 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow">
                                    <div class="font-medium dark:text-gray-200 mb-1 truncate">{{ $message->subject ?? 'No Subject' }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $message->from ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $message->snippet ?? '' }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 mt-4">No messages found</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Stats Card -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900 dark:to-indigo-900 p-6 rounded-xl">
                    <h3 class="text-lg font-medium dark:text-gray-200 mb-4">Gmail Integration</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Status</span>
                            <span class="text-sm font-medium text-green-600 dark:text-green-400">Active</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Messages Available</span>
                            <span class="text-sm font-medium dark:text-gray-200">{{ count($messages) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Last Sync</span>
                            <span class="text-sm font-medium dark:text-gray-200">Just now</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::section>
    @else
        <!-- Not Authenticated State -->
        <x-filament::section>
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-6">
                    <svg class="h-8 w-8 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>

                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">Connect Your Gmail Account</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Connect your Gmail account to access emails, manage contacts, and streamline your client communications.
                </p>

                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-danger-100 text-danger-700 dark:bg-danger-900 dark:text-danger-300 mb-6">
                    {{ $this->getAuthStatusMessage() }}
                </div>
            </div>
        </x-filament::section>

        <!-- Setup Instructions - Only show when not authenticated -->
        <x-filament::section>
            <h2 class="text-xl font-semibold mb-4 dark:text-gray-200">Setup Instructions</h2>

            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-blue-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        You'll need to set up a Google Cloud project and configure OAuth credentials before connecting Gmail.
                    </p>
                </div>
            </div>

            <ol class="list-decimal list-inside space-y-3 text-gray-700 dark:text-gray-300">
                <li>Create a new project in the <a href="https://console.developers.google.com/" target="_blank" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">Google Developer Console</a></li>
                <li>Enable the Gmail API</li>
                <li>Configure the OAuth consent screen</li>
                <li>Create OAuth credentials (Web application type)</li>
                <li>Add the redirect URI:
                    <code class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-sm dark:text-gray-300">{{ config('gmail-client.redirect_uri') }}</code>
                </li>
                <li>Add the Client ID and Client Secret to your <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded dark:text-gray-300">.env</code> file:
    <pre class="bg-gray-100 dark:bg-gray-700 p-2 rounded text-sm mt-1 dark:text-gray-300">
    GMAIL_CLIENT_ID=your-client-id
    GMAIL_CLIENT_SECRET=your-client-secret
    GMAIL_REDIRECT_URI={{ config('gmail-client.redirect_uri') }}
    GMAIL_FROM_EMAIL=your-email@gmail.com
    GMAIL_REGISTER_ROUTES=true
    </pre>
                </li>
            </ol>
        </x-filament::section>
    @endif
</x-filament::page>