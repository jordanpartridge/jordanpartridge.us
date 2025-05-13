<x-filament::page>
    <x-filament::section>
        <h2 class="text-xl font-semibold mb-2">Authentication Status</h2>

        <div class="flex items-center mb-6">
            <div @class([
                'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium me-3',
                'bg-success-100 text-success-700' => $this->isAuthenticated(),
                'bg-danger-100 text-danger-700' => !$this->isAuthenticated(),
            ])>
                {{ $this->isAuthenticated() ? 'Authenticated' : 'Not Authenticated' }}
            </div>
            <span class="text-gray-500 text-sm">{{ $this->getAuthStatusMessage() }}</span>
        </div>

        <!-- Debug Information -->
        <div class="mt-4 p-4 dark:bg-gray-800 bg-gray-50 rounded-md text-xs font-mono">
            <h3 class="font-semibold text-sm mb-2 dark:text-gray-200">Debug Info:</h3>
            <div class="mb-2">
                <div class="font-bold dark:text-gray-300">Access Token:</div>
                <div class="truncate dark:text-gray-400">{{ $this->accessToken ?? 'Not set' }}</div>
            </div>
            <div class="mb-2">
                <div class="font-bold dark:text-gray-300">Refresh Token:</div>
                <div class="dark:text-gray-400">{{ $this->refreshToken ?? 'Not set' }}</div>
            </div>
            <div class="mb-2">
                <div class="font-bold dark:text-gray-300">Expires At:</div>
                <div class="dark:text-gray-400">{{ $this->tokenExpires ?? 'Not set' }}</div>
            </div>
            <div>
                <div class="font-bold dark:text-gray-300">Authentication Status:</div>
                <div class="dark:text-gray-400">{{ auth()->user()->hasValidGmailToken() ? 'Valid token' : 'No valid token' }}</div>
            </div>
        </div>

        @if ($this->isAuthenticated())
            <div class="mt-4">
                <h3 class="text-lg font-medium mb-2 dark:text-gray-200">Quick Preview</h3>
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <h4 class="text-md font-medium mb-2 dark:text-gray-200">Recent Messages</h4>
                    @php
                        $messages = $this->getMessages();
                    @endphp

                    @if (count($messages))
                        <ul class="space-y-2">
                            @foreach (array_slice($messages, 0, 3) as $message)
                                <li class="p-3 bg-white dark:bg-gray-700 rounded shadow-sm">
                                    <div class="font-medium dark:text-gray-200">{{ $message->subject ?? 'No Subject' }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">From: {{ $message->from ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate mt-1">{{ $message->snippet ?? '' }}</div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-2 text-right">
                            <a href="{{ route('filament.admin.pages.gmail-messages') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 text-sm">
                                View all messages &rarr;
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No messages found or error fetching messages.</p>
                    @endif
                </div>
            </div>
        @endif
    </x-filament::section>

    <x-filament::section>
        <h2 class="text-xl font-semibold mb-4 dark:text-gray-200">Setup Instructions</h2>

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
</x-filament::page>