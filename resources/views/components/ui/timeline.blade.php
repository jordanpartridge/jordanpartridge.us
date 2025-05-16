@props(['items' => [], 'animated' => true])

<div {{ $attributes->merge(['class' => 'relative']) }}>
    <!-- Vertical line -->
    <div class="absolute left-5 md:left-1/2 top-0 h-full w-0.5 bg-gradient-to-b from-primary-500 via-purple-500 to-secondary-500 transform -translate-x-1/2"></div>

    <div class="space-y-16">
        @foreach ($items as $index => $item)
            @php
                $color = $item['color'] ?? ($index % 2 == 0 ? 'primary' : 'secondary');
                $side = $index % 2 == 0 ? 'right' : 'left';
            @endphp

            <div class="relative {{ $animated ? 'reveal' : '' }}" x-intersect.once="$el.classList.add('active')" x-data="{ isHovered: false }" @mouseenter="isHovered = true" @mouseleave="isHovered = false">
                <!-- Dot on the timeline -->
                <div class="absolute left-5 md:left-1/2 top-5 w-5 h-5 rounded-full bg-gradient-to-r from-{{ $color }}-500 to-{{ $color }}-600 shadow-md transform -translate-x-1/2 z-10 transition-all duration-300" :class="{ 'scale-150': isHovered }"></div>

                <div class="flex flex-col md:flex-row items-center">
                    <!-- Date on left side for desktop -->
                    <div class="md:w-1/2 mb-4 md:mb-0 md:pr-12 text-right hidden md:block">
                        @if ($side == 'right')
                            <span class="inline-block px-4 py-2 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-lg font-semibold shadow-sm">
                                {{ $item['date'] ?? '' }}
                            </span>
                            <div class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ $item['title'] ?? '' }}</div>
                            <div class="text-gray-600 dark:text-gray-300">{{ $item['subtitle'] ?? '' }}</div>
                        @endif
                    </div>

                    <!-- Content container -->
                    <div class="md:w-1/2 {{ $side == 'right' ? 'ml-12 md:ml-0 md:pl-12' : 'ml-12 md:ml-0 md:pr-12 md:text-right' }}">
                        <!-- Mobile date display -->
                        <div class="md:hidden mb-2">
                            <span class="inline-block px-4 py-2 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-lg font-semibold shadow-sm">
                                {{ $item['date'] ?? '' }}
                            </span>
                            <div class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ $item['title'] ?? '' }}</div>
                            <div class="text-gray-600 dark:text-gray-300">{{ $item['subtitle'] ?? '' }}</div>
                        </div>

                        <!-- Content card -->
                        <div class="relative bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-lg p-6 shadow-lg border border-{{ $color }}-200 dark:border-{{ $color }}-800/50 transition-all duration-300 hover:shadow-xl group overflow-hidden">
                            <!-- Background gradient effect on hover -->
                            <div class="absolute inset-0 bg-gradient-to-br from-{{ $color }}-500/5 to-{{ $color }}-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></div>

                            <div class="prose prose-sm dark:prose-invert max-w-none relative z-10">
                                {!! $item['content'] ?? '' !!}
                            </div>

                            @if (isset($item['tags']) && is_array($item['tags']) && count($item['tags']) > 0)
                                <div class="mt-4 flex flex-wrap gap-2 {{ $side == 'left' ? 'md:justify-end' : '' }} relative z-10">
                                    @foreach ($item['tags'] as $tag)
                                        <span class="px-3 py-1 text-xs font-medium bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-full">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Date on right side -->
                    <div class="md:w-1/2 mt-4 md:mt-0 md:pl-12 hidden md:block">
                        @if ($side == 'left')
                            <span class="inline-block px-4 py-2 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-lg font-semibold shadow-sm">
                                {{ $item['date'] ?? '' }}
                            </span>
                            <div class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ $item['title'] ?? '' }}</div>
                            <div class="text-gray-600 dark:text-gray-300">{{ $item['subtitle'] ?? '' }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>