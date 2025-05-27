<div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 dark:from-primary-600 dark:to-primary-700 p-6 text-white">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10">
        <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1" fill="currentColor" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dots)" />
        </svg>
    </div>

    <div class="relative">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- Main Briefing --}}
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">{{ $briefing['greeting'] }}, Jordan</h2>

                <div class="flex flex-wrap items-center gap-4 text-sm text-primary-100">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-calendar class="w-4 h-4" />
                        <span>{{ now()->format('l, F j') }}</span>
                    </div>

                    @if ($weather)
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-sun class="w-4 h-4" />
                            <span>{{ $weather }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <x-heroicon-o-clock class="w-4 h-4" />
                        <span>{{ now()->format('g:i A') }}</span>
                    </div>
                </div>

                @if (count($briefing['insights']) > 0)
                    <div class="mt-3 space-y-1">
                        @foreach ($briefing['insights'] as $insight)
                            <p class="text-sm text-primary-100 flex items-center gap-2">
                                <span class="w-1 h-1 bg-primary-200 rounded-full"></span>
                                {{ $insight }}
                            </p>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="flex items-center gap-2">
                <x-filament::button
                    color="white"
                    size="sm"
                    icon="heroicon-o-plus"
                    href="{{ route('filament.admin.resources.posts.create') }}"
                >
                    New Post
                </x-filament::button>

                <x-filament::button
                    color="white"
                    size="sm"
                    outlined
                    icon="heroicon-o-calendar"
                    href="https://calendar.google.com"
                    target="_blank"
                >
                    Calendar
                </x-filament::button>
            </div>
        </div>

        {{-- Focus Time Suggestion --}}
        @if ($briefing['focus_time'])
            <div class="mt-4 p-3 bg-white/10 rounded-lg backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-light-bulb class="w-5 h-5 text-yellow-300" />
                        <span class="text-sm font-medium">
                            {{ $briefing['focus_time']['type'] }}:
                            {{ $briefing['focus_time']['start'] }} - {{ $briefing['focus_time']['end'] }}
                        </span>
                    </div>
                    <x-filament::link
                        color="white"
                        size="sm"
                        icon="heroicon-m-calendar"
                    >
                        Block time
                    </x-filament::link>
                </div>
            </div>
        @endif

        {{-- Daily Quote --}}
        @if ($quote)
            <div class="mt-4 text-center">
                <p class="text-sm text-primary-100 italic">
                    "{{ $quote }}"
                </p>
            </div>
        @endif
    </div>
</div>
