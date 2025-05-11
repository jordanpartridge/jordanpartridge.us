<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4">
        <!-- Focus on key client -->
        @if ($focusedClient)
            <x-filament::section>
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white flex items-center">
                            <span class="mr-2">🌟</span> Client Focus: {{ $focusedClient->name }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if ($focusedClient->last_contact_at)
                                Last contacted {{ $focusedClient->last_contact_at->diffForHumans() }}
                            @else
                                No previous contact recorded
                            @endif
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="mailto:{{ $focusedClient->email }}" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                            @svg('heroicon-s-envelope', 'w-5 h-5 -ml-1')
                            Send Email
                        </a>
                        @if ($focusedClient->phone)
                            <a href="tel:{{ $focusedClient->phone }}" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 shadow focus:ring-gray-300 border-gray-300 bg-white hover:bg-gray-50 dark:text-white dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                                @svg('heroicon-s-phone', 'w-5 h-5 -ml-1')
                                Call
                            </a>
                        @endif
                        <a href="{{ route('filament.admin.resources.clients.view', $focusedClient) }}" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-blue-700 border-blue-600 bg-white hover:bg-blue-50 dark:text-blue-500 dark:border-blue-600 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-blue-600 shadow">
                            @svg('heroicon-s-eye', 'w-5 h-5 -ml-1')
                            View Details
                        </a>
                        <form action="{{ route('filament.admin.resources.clients.view', $focusedClient) }}" method="GET">
                            <button type="button" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-green-700 border-green-600 bg-white hover:bg-green-50 dark:text-green-500 dark:border-green-600 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-green-600 shadow">
                                @svg('heroicon-s-check', 'w-5 h-5 -ml-1')
                                Log Contact
                            </button>
                        </form>
                    </div>
                </div>
            </x-filament::section>

            <!-- Upload Documents Section -->
            <x-filament::section>
                <x-slot name="heading">Upload Document</x-slot>

                <form wire:submit="saveDocument">
                    {{ $this->form }}

                    <div class="mt-4">
                        <x-filament::button type="submit">
                            Upload Document
                        </x-filament::button>
                    </div>
                </form>
            </x-filament::section>

            <!-- Client Documents -->
            <x-filament::section>
                <x-slot name="heading">Client Documents</x-slot>

                @if ($documents->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($documents as $document)
                        <li class="py-3 flex items-center justify-between">
                            <div class="flex items-center">
                                @switch($document->mime_type)
                                    @case('application/pdf')
                                        @svg('heroicon-s-document', 'w-5 h-5 text-red-500 mr-3')
                                        @break
                                    @case('application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                        @svg('heroicon-s-document-text', 'w-5 h-5 text-blue-500 mr-3')
                                        @break
                                    @case('application/vnd.ms-excel')
                                    @case('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                        @svg('heroicon-s-document-chart-bar', 'w-5 h-5 text-green-500 mr-3')
                                        @break
                                    @default
                                        @svg('heroicon-s-document', 'w-5 h-5 text-gray-400 mr-3')
                                @endswitch
                                <div>
                                    <div class="font-medium">{{ $document->original_filename }}</div>
                                    @if ($document->description)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $document->description }}</div>
                                    @endif
                                    <div class="text-xs text-gray-400 mt-1">
                                        Uploaded {{ $document->created_at->diffForHumans() }} by {{ $document->uploadedBy->name }} • {{ $document->file_size_for_humans }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ $document->download_url }}" class="text-primary-600 dark:text-primary-500 hover:underline">Download</a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                        No documents uploaded yet.
                    </div>
                @endif
            </x-filament::section>

            <!-- Quick actions row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-filament::section>
                    <h3 class="text-lg font-medium">Create Invoice</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Generate a new invoice for this client</p>
                    <button type="button" class="w-full inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                        @svg('heroicon-s-document-text', 'w-5 h-5')
                        New Invoice
                    </button>
                </x-filament::section>

                <x-filament::section>
                    <h3 class="text-lg font-medium">Schedule Meeting</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Book time on the calendar with this client</p>
                    <button type="button" class="w-full inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                        @svg('heroicon-s-calendar', 'w-5 h-5')
                        Schedule
                    </button>
                </x-filament::section>

                <x-filament::section>
                    <h3 class="text-lg font-medium">Add Note</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Record information about this client</p>
                    <button type="button" class="w-full inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                        @svg('heroicon-s-pencil', 'w-5 h-5')
                        Add Note
                    </button>
                </x-filament::section>
            </div>

            <!-- Export option at the bottom -->
            <div class="mt-4 text-right">
                <a href="{{ route('clients.export') }}" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-600 shadow-sm focus:ring-gray-300 border-gray-300 bg-white hover:bg-gray-50 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                    @svg('heroicon-s-document-arrow-down', 'w-5 h-5 -ml-1')
                    Export Client Data
                </a>
            </div>
        @else
            <x-filament::section>
                <div class="text-center py-6">
                    <div class="text-lg font-medium mb-2">No focused client selected</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Select a client to focus on by using the "Focus" action in the client list
                    </p>
                    <a href="{{ route('filament.admin.resources.clients.index') }}" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                        @svg('heroicon-s-users', 'w-5 h-5 -ml-1')
                        Go to Clients
                    </a>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
