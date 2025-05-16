@props(['items' => [], 'animated' => true])

<div {{ $attributes->merge(['class' => 'relative timeline-component']) }}>
    <!-- Vertical line with solid color - simpler and more consistent -->
    <div class="absolute left-5 md:left-1/2 top-0 h-full w-0.5 bg-gray-200 dark:bg-gray-700 transform -translate-x-1/2 z-0"></div>

    <div class="space-y-12">
        @foreach ($items as $index => $item)
            @php
                $color = $item['color'] ?? ($index % 2 == 0 ? 'blue' : 'indigo');
                $side = $index % 2 == 0 ? 'right' : 'left';
                $delay = $index * 0.1; // Staggered animation delay
            @endphp

            <div
                class="relative {{ $animated ? 'opacity-0 translate-y-4' : '' }} timeline-item"
                x-intersect.once="$el.classList.add('animate-timeline-item')"
                x-data="{ isHovered: false }"
                @mouseenter="isHovered = true"
                @mouseleave="isHovered = false"
                data-delay="{{ $delay }}"
            >
                <!-- Dot indicator - simplified for better alignment -->
                <div
                    class="absolute left-5 md:left-1/2 top-5 w-3.5 h-3.5 rounded-full bg-{{ $color }}-500 dark:bg-{{ $color }}-400 border-2 border-white dark:border-gray-900 transform -translate-x-1/2 z-10 transition-all duration-300"
                    :class="{ 'scale-125': isHovered }"
                ></div>

                <div class="flex flex-col md:flex-row items-start"> <!-- Changed from items-center to items-start for better alignment -->
                    <!-- Date/title on left for even items on desktop -->
                    <div class="md:w-1/2 mb-4 md:mb-0 md:pr-8 text-right hidden md:block">
                        @if ($side == 'right')
                            <span class="inline-block px-2.5 py-1 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-700 dark:text-{{ $color }}-300 rounded-md font-medium text-xs">
                                {{ $item['date'] ?? '' }}
                            </span>
                            <div class="mt-2 text-lg font-bold text-gray-900 dark:text-white">{{ $item['title'] ?? '' }}</div>
                            <div class="text-gray-500 dark:text-gray-400">{{ $item['subtitle'] ?? '' }}</div>
                        @endif
                    </div>

                    <!-- Content container -->
                    <div class="md:w-1/2 {{ $side == 'right' ? 'ml-10 md:ml-0 md:pl-8' : 'ml-10 md:ml-0 md:pr-8 md:text-right' }}">
                        <!-- Mobile date display -->
                        <div class="md:hidden mb-2">
                            <span class="inline-block px-2.5 py-1 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-700 dark:text-{{ $color }}-300 rounded-md font-medium text-xs">
                                {{ $item['date'] ?? '' }}
                            </span>
                            <div class="mt-2 text-lg font-bold text-gray-900 dark:text-white">{{ $item['title'] ?? '' }}</div>
                            <div class="text-gray-500 dark:text-gray-400">{{ $item['subtitle'] ?? '' }}</div>
                        </div>

                        <!-- Content card with refined styling -->
                        <div
                            class="relative bg-white dark:bg-gray-800 rounded-lg p-5 shadow border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-300 group overflow-hidden"
                        >
                            <!-- Left border accent - simplified design -->
                            <div class="absolute left-0 top-0 w-1 h-full bg-{{ $color }}-500 dark:bg-{{ $color }}-400"></div>

                            <div class="prose prose-sm dark:prose-invert max-w-none relative z-10 pl-3"> <!-- Added pl-3 for padding from the accent border -->
                                @if (isset($item['content']))
                                    {!! e($item['content'], false) !!}
                                @endif
                            </div>

                            @if (isset($item['tags']) && is_array($item['tags']) && count($item['tags']) > 0)
                                <div class="mt-4 flex flex-wrap gap-1.5 {{ $side == 'left' ? 'md:justify-end' : '' }} relative z-10 pl-3"> <!-- Added pl-3 to match content -->
                                    @foreach ($item['tags'] as $tag)
                                        <span class="px-2 py-0.5 text-xs font-medium bg-{{ $color }}-100 dark:bg-{{ $color }}-900/50 text-{{ $color }}-700 dark:text-{{ $color }}-300 rounded-md">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Date/title on right for odd items -->
                    <div class="md:w-1/2 mt-4 md:mt-0 md:pl-8 hidden md:block">
                        @if ($side == 'left')
                            <span class="inline-block px-2.5 py-1 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-700 dark:text-{{ $color }}-300 rounded-md font-medium text-xs">
                                {{ $item['date'] ?? '' }}
                            </span>
                            <div class="mt-2 text-lg font-bold text-gray-900 dark:text-white">{{ $item['title'] ?? '' }}</div>
                            <div class="text-gray-500 dark:text-gray-400">{{ $item['subtitle'] ?? '' }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Animations moved to bike-joy-enhancements.css -->
</div>