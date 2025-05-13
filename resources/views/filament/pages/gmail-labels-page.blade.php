<x-filament::page>
    <x-filament::section>
        @if (count($labels))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($labels as $label)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <div class="flex items-center">
                            <span @class([
                                'h-4 w-4 rounded-full mr-2',
                                'bg-primary-500' => $label->type === 'system',
                                'bg-success-500' => $label->type === 'user',
                            ])></span>
                            <h3 class="text-lg font-medium">{{ $label->name ?? 'Unnamed Label' }}</h3>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            <div>ID: <span class="font-mono">{{ $label->id ?? 'Unknown' }}</span></div>
                            <div>Type: {{ ucfirst($label->type ?? 'Unknown') }}</div>
                            <div>Messages: {{ $label->messagesTotal ?? 0 }}</div>
                            <div>Unread: {{ $label->messagesUnread ?? 0 }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center p-8">
                <div class="text-center">
                    <x-filament::icon
                        icon="heroicon-o-tag"
                        class="mx-auto h-12 w-12 text-gray-400"
                    />
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No labels found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        There are no labels in your Gmail account or there was an issue fetching them.
                    </p>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament::page>