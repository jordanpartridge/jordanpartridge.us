<x-filament::page>
    <x-filament::section>
        @if (count($messages))
            <div class="overflow-x-auto">
                <table class="w-full text-left rtl:text-right divide-y table-auto">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-sm font-medium text-gray-500">From</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-500">Subject</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-500">Date</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-500">Preview</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($messages as $message)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">
                                    {{ $message->from ?? 'Unknown Sender' }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium">
                                    {{ $message->subject ?? 'No Subject' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    {{ isset($message->date) ? \Carbon\Carbon::parse($message->date)->format('M d, Y h:i A') : 'Unknown Date' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $message->snippet ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex items-center justify-center p-8">
                <div class="text-center">
                    <x-filament::icon
                        icon="heroicon-o-inbox"
                        class="mx-auto h-12 w-12 text-gray-400"
                    />
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No messages found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        There are no messages in your Gmail account or there was an issue fetching them.
                    </p>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament::page>