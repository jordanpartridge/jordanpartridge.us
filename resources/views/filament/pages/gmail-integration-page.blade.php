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
        <!-- Not Authenticated State - Clean & Modern -->
        <x-filament::section>
            <div class="text-center py-16">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-blue-100 to-indigo-200 dark:from-blue-900/50 dark:to-indigo-900/50 mb-8">
                    <svg class="h-10 w-10 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Connect Your Gmail Account</h3>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-12 max-w-2xl mx-auto">
                    Access your emails, manage client communications, and streamline your workflow with Gmail integration.
                </p>

                <!-- Feature Highlights -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto mb-12">
                    <div class="text-center p-6 rounded-xl bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/20">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Smart Organization</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Automatically categorize emails by clients, prospects, and priority</p>
                    </div>

                    <div class="text-center p-6 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Client Integration</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Connect emails with your CRM and client database automatically</p>
                    </div>

                    <div class="text-center p-6 rounded-xl bg-gradient-to-br from-purple-50 to-violet-100 dark:from-purple-900/20 dark:to-violet-900/20">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Lightning Fast</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Quick preview, search, and actions to boost your productivity</p>
                    </div>
                </div>
            </div>
        </x-filament::section>

    @endif
</x-filament::page>