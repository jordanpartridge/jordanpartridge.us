<x-filament-panels::page>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Action buttons at the top of the page -->
    <div class="flex justify-between mb-4">
        <a href="{{ route('filament.admin.resources.clients.index') }}" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 shadow focus:ring-gray-300 border-gray-300 bg-white hover:bg-gray-50 dark:text-white dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
            @svg('heroicon-s-users', 'w-5 h-5 -ml-1')
            Manage Clients
        </a>

        <a href="{{ route('filament.admin.resources.clients.create') }}" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
            @svg('heroicon-s-plus', 'w-5 h-5 -ml-1')
            Create New Client
        </a>
    </div>

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
                        <form action="{{ route('clients.log-contact', $focusedClient) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-green-700 border-green-600 bg-white hover:bg-green-50 dark:text-green-500 dark:border-green-600 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-green-600 shadow">
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

            <!-- Client Documents with Filtering & Security -->
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center justify-between">
                        <div>Client Documents</div>
                        @if ($documents->count() > 0)
                            <div class="text-sm text-gray-500">{{ $documents->count() }} {{ Str::plural('document', $documents->count()) }}</div>
                        @endif
                    </div>
                </x-slot>

                @if ($documents->count() > 0)
                    <!-- Document Filters -->
                    <div class="mb-4 flex flex-col md:flex-row gap-2">
                        <div class="relative rounded-md shadow-sm flex-1">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                @svg('heroicon-s-magnifying-glass', 'w-4 h-4 text-gray-400')
                            </div>
                            <input type="text" id="document-search" placeholder="Search documents..." class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600 dark:placeholder:text-gray-400 dark:focus:ring-primary-500">
                        </div>

                        <select id="document-type-filter" class="h-9 rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600 dark:focus:ring-primary-500">
                            <option value="">All Files</option>
                            <option value="pdf">PDF Documents</option>
                            <option value="word">Word Documents</option>
                            <option value="excel">Spreadsheets</option>
                            <option value="image">Images</option>
                        </select>
                    </div>

                    <!-- Document List with Enhanced Security & UX -->
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700" id="document-list">
                        @foreach ($documents as $document)
                        <li class="py-3 flex flex-col sm:flex-row sm:items-center justify-between document-item"
                            data-filename="{{ $document->original_filename }}"
                            data-type="{{ $document->mime_type }}"
                            data-description="{{ $document->description }}">
                            <div class="flex items-start sm:items-center mb-2 sm:mb-0">
                                @svg($document->display_icon, 'w-5 h-5 mr-3 flex-shrink-0 mt-1 sm:mt-0')
                                <div class="min-w-0 flex-1">
                                    <div class="font-medium truncate max-w-md">{{ $document->original_filename }}</div>
                                    @if ($document->description)
                                        <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">{{ $document->description }}</div>
                                    @endif
                                    <div class="text-xs text-gray-400 mt-1 flex flex-wrap gap-x-2">
                                        <span title="{{ $document->created_at->format('F j, Y g:i A') }}">Uploaded {{ $document->created_at->diffForHumans() }}</span>
                                        <span>•</span>
                                        <span>{{ $document->uploadedBy->name }}</span>
                                        <span>•</span>
                                        <span>{{ $document->file_size_for_humans }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2 ml-8 sm:ml-0">
                                <a href="{{ $document->download_url }}"
                                   class="text-primary-600 dark:text-primary-400 hover:underline flex items-center"
                                   title="Download this document"
                                   data-confirm="{{ $document->file_size > 5242880 ? 'true' : 'false' }}"
                                   onclick="if(this.dataset.confirm === 'true' && !confirm('This is a large file ({{ $document->file_size_for_humans }}). Continue with download?')) { return false; }">
                                    @svg('heroicon-s-arrow-down-tray', 'w-4 h-4 mr-1')
                                    <span class="hidden sm:inline">Download</span>
                                </a>

                                @if (str_contains($document->mime_type, 'pdf'))
                                    <a href="{{ $document->signed_url }}"
                                       target="_blank"
                                       class="text-gray-600 dark:text-gray-300 hover:underline flex items-center"
                                       title="View this PDF in a new tab">
                                        @svg('heroicon-s-eye', 'w-4 h-4 mr-1')
                                        <span class="hidden sm:inline">View</span>
                                    </a>
                                @endif

                                <!-- Only show delete option to the uploader or administrators -->
                                @if (auth()->id() === $document->uploaded_by || auth()->user()->hasRole('admin'))
                                    <button
                                        type="button"
                                        class="text-danger-600 dark:text-danger-400 hover:underline flex items-center"
                                        title="Delete this document"
                                        data-document-id="{{ $document->id }}"
                                        onclick="deleteDocument({{ $document->id }}, '{{ $document->original_filename }}')">
                                        @svg('heroicon-s-trash', 'w-4 h-4 mr-1')
                                        <span class="hidden sm:inline">Delete</span>
                                    </button>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Document filtering and deletion scripts -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const searchInput = document.getElementById('document-search');
                            const typeFilter = document.getElementById('document-type-filter');
                            const documentItems = document.querySelectorAll('.document-item');

                            function filterDocuments() {
                                const searchTerm = searchInput.value.toLowerCase();
                                const selectedType = typeFilter.value.toLowerCase();

                                documentItems.forEach(item => {
                                    const filename = item.dataset.filename.toLowerCase();
                                    const description = item.dataset.description.toLowerCase();
                                    const mimeType = item.dataset.type.toLowerCase();

                                    const matchesSearch = filename.includes(searchTerm) || description.includes(searchTerm);
                                    const matchesType = !selectedType || mimeType.includes(selectedType);

                                    item.style.display = (matchesSearch && matchesType) ? 'flex' : 'none';
                                });

                                // Show/hide empty state if no results
                                const hasVisibleItems = Array.from(documentItems).some(item => item.style.display !== 'none');
                                document.getElementById('no-results-message').style.display = hasVisibleItems ? 'none' : 'block';
                            }

                            searchInput.addEventListener('input', filterDocuments);
                            typeFilter.addEventListener('change', filterDocuments);
                        });

                        // Document deletion function
                        function deleteDocument(documentId, filename) {
                            if (!confirm(`Are you sure you want to delete "${filename}"? This action cannot be undone.`)) {
                                return;
                            }

                            // Show loading state
                            const button = document.querySelector(`[data-document-id="${documentId}"]`);
                            const originalHTML = button.innerHTML;
                            button.innerHTML = `<svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                              </svg><span class="hidden sm:inline">Deleting...</span>`;
                            button.disabled = true;

                            // Create CSRF token
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            // Send delete request
                            fetch(`/client-documents/${documentId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove document item from the list with animation
                                    const documentItem = button.closest('.document-item');
                                    documentItem.style.transition = 'opacity 0.3s, transform 0.3s';
                                    documentItem.style.opacity = 0;
                                    documentItem.style.transform = 'translateX(20px)';

                                    setTimeout(() => {
                                        documentItem.remove();

                                        // Update document count
                                        const remainingCount = document.querySelectorAll('.document-item').length;
                                        if (remainingCount === 0) {
                                            const listElement = document.getElementById('document-list');
                                            listElement.innerHTML = `<div class="text-center py-6 text-gray-500 dark:text-gray-400">
                                                No documents uploaded yet.
                                            </div>`;
                                        }

                                        // Show success notification
                                        const event = new CustomEvent('notify', {
                                            detail: {
                                                title: 'Success',
                                                message: 'Document deleted successfully',
                                                type: 'success',
                                            }
                                        });
                                        window.dispatchEvent(event);
                                    }, 300);
                                } else {
                                    // Reset button state
                                    button.innerHTML = originalHTML;
                                    button.disabled = false;

                                    // Show error notification
                                    const event = new CustomEvent('notify', {
                                        detail: {
                                            title: 'Error',
                                            message: data.message || 'Failed to delete document',
                                            type: 'danger',
                                        }
                                    });
                                    window.dispatchEvent(event);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);

                                // Reset button state
                                button.innerHTML = originalHTML;
                                button.disabled = false;

                                // Show error notification
                                const event = new CustomEvent('notify', {
                                    detail: {
                                        title: 'Error',
                                        message: 'An unexpected error occurred',
                                        type: 'danger',
                                    }
                                });
                                window.dispatchEvent(event);
                            });
                        }
                    </script>

                    <!-- No results message (initially hidden) -->
                    <div id="no-results-message" class="text-center py-6 text-gray-500 dark:text-gray-400 hidden">
                        No documents match your search criteria.
                    </div>
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

            <!-- Export options section -->
            <x-filament::section>
                <x-slot name="heading">Export Client Data</x-slot>

                <form action="{{ route('clients.export') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium mb-1">Status</label>
                            <select name="status[]" id="status" multiple class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="{{ App\Enums\ClientStatus::LEAD->value }}">Lead</option>
                                <option value="{{ App\Enums\ClientStatus::ACTIVE->value }}">Active</option>
                                <option value="{{ App\Enums\ClientStatus::FORMER->value }}">Former</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple</p>
                        </div>

                        <div>
                            <label for="user_id" class="block text-sm font-medium mb-1">Account Manager</label>
                            <select name="user_id" id="user_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">All Account Managers</option>
                                @foreach (\App\Models\User::query()->orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <div class="flex items-start pt-5">
                                <div class="flex items-center h-5">
                                    <input id="focused" name="focused" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-primary-600 dark:focus:ring-offset-gray-800">
                                </div>
                                <div class="ml-3">
                                    <label for="focused" class="text-sm font-medium text-gray-700 dark:text-gray-200">Focused Clients Only</label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Export only dashboard-focused clients</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium mb-1">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium mb-1">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                            @svg('heroicon-s-document-arrow-down', 'w-5 h-5 -ml-1')
                            Export Client Data
                        </button>
                    </div>
                </form>
            </x-filament::section>
        @else
            <x-filament::section>
                <div class="text-center py-6">
                    <div class="text-lg font-medium mb-2">No focused client selected</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Select a client to focus on by using the "Focus" action in the client list
                    </p>
                    <div class="flex justify-center gap-3">
                        <a href="{{ route('filament.admin.resources.clients.index') }}" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 shadow focus:ring-gray-300 border-gray-300 bg-white hover:bg-gray-50 dark:text-white dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                            @svg('heroicon-s-users', 'w-5 h-5 -ml-1')
                            Select Existing Client
                        </a>
                        <a href="{{ route('filament.admin.resources.clients.create') }}" class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                            @svg('heroicon-s-plus', 'w-5 h-5 -ml-1')
                            Create New Client
                        </a>
                    </div>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
