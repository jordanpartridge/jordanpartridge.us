@props(['items' => [], 'animated' => true])

<div {{ $attributes->merge(['class' => 'relative timeline-component']) }}>
    <!-- Vertical line with animated gradient -->
    <div class="absolute left-5 md:left-1/2 top-0 h-full w-px bg-gradient-to-b from-primary-500 via-blue-500 to-secondary-500 transform -translate-x-1/2 z-0">
        <!-- Sliding animation dot -->
        <div class="absolute top-0 left-1/2 w-3 h-3 bg-white dark:bg-gray-800 rounded-full shadow-md transform -translate-x-1/2 timeline-pulse"></div>
    </div>

    <div class="space-y-12">
        @foreach ($items as $index => $item)
            @php
                $color = $item['color'] ?? ($index % 2 == 0 ? 'primary' : 'secondary');
                $side = $index % 2 == 0 ? 'right' : 'left';
                $delay = $index * 0.1; // Staggered animation delay
            @endphp

            <div
                class="relative {{ $animated ? 'opacity-0 translate-y-4' : '' }}"
                x-intersect.once="$el.classList.add('animate-timeline-item')"
                x-data="{ isHovered: false }"
                @mouseenter="isHovered = true"
                @mouseleave="isHovered = false"
                style="{{ $animated ? '--delay: ' . $delay . 's' : '' }}"
            >
                <!-- Dot indicator with pulse effect -->
                <div
                    class="absolute left-5 md:left-1/2 top-5 w-4 h-4 rounded-full bg-gradient-to-r from-{{ $color }}-500 to-{{ $color }}-600 shadow-md transform -translate-x-1/2 z-10 transition-all duration-300 ring-2 ring-white dark:ring-gray-900"
                    :class="{ 'scale-125 ring-opacity-50 ring-{{ $color }}-500 dark:ring-{{ $color }}-400': isHovered }"
                >
                    <!-- Inner pulse animation -->
                    <span class="absolute inset-0 rounded-full bg-{{ $color }}-400 animate-ping opacity-75"></span>
                </div>

                <div class="flex flex-col md:flex-row items-center">
                    <!-- Date/title on left for even items on desktop -->
                    <div class="md:w-1/2 mb-4 md:mb-0 md:pr-8 text-right hidden md:block">
                        @if ($side == 'right')
                            <span class="inline-block px-3 py-1 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/20 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-md font-semibold text-sm shadow-sm">
                                {{ $item['date'] ?? '' }}
                            </span>
                            <div class="mt-2 text-lg font-bold bg-gradient-to-br from-{{ $color }}-700 to-{{ $color }}-900 dark:from-{{ $color }}-300 dark:to-{{ $color }}-500 text-transparent bg-clip-text">{{ $item['title'] ?? '' }}</div>
                            <div class="text-gray-600 dark:text-gray-400">{{ $item['subtitle'] ?? '' }}</div>
                        @endif
                    </div>

                    <!-- Content container -->
                    <div class="md:w-1/2 {{ $side == 'right' ? 'ml-10 md:ml-0 md:pl-8' : 'ml-10 md:ml-0 md:pr-8 md:text-right' }}">
                        <!-- Mobile date display -->
                        <div class="md:hidden mb-2">
                            <span class="inline-block px-3 py-1 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/20 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-md font-semibold text-sm shadow-sm">
                                {{ $item['date'] ?? '' }}
                            </span>
                            <div class="mt-2 text-lg font-bold bg-gradient-to-br from-{{ $color }}-700 to-{{ $color }}-900 dark:from-{{ $color }}-300 dark:to-{{ $color }}-500 text-transparent bg-clip-text">{{ $item['title'] ?? '' }}</div>
                            <div class="text-gray-600 dark:text-gray-400">{{ $item['subtitle'] ?? '' }}</div>
                        </div>

                        <!-- Content card with refined styling -->
                        <div
                            class="relative bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-lg p-5 shadow-lg border border-{{ $color }}-200 dark:border-{{ $color }}-800/50 hover:border-{{ $color }}-300 dark:hover:border-{{ $color }}-700/70 transition-all duration-300 hover:shadow-xl group overflow-hidden"
                            :class="{'shadow-{{ $color }}-200/50 dark:shadow-{{ $color }}-900/20': isHovered}"
                        >
                            <!-- Enhanced gradient effect on hover with slight opacity -->
                            <div class="absolute inset-0 bg-gradient-to-br from-{{ $color }}-50 via-{{ $color }}-100/10 to-{{ $color }}-200/20 dark:from-{{ $color }}-900/10 dark:via-{{ $color }}-800/5 dark:to-{{ $color }}-700/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-lg"></div>

                            <!-- Accent bar at the top or side depending on layout -->
                            <div class="{{ $side == 'right' ? 'absolute top-0 left-0 w-full h-1' : 'absolute top-0 right-0 w-full h-1' }} md:{{ $side == 'right' ? 'left-0 top-0 h-full w-1' : 'right-0 top-0 h-full w-1' }} bg-gradient-to-r from-{{ $color }}-400 to-{{ $color }}-600 rounded-t-lg md:rounded-none md:{{ $side == 'right' ? 'rounded-l-lg' : 'rounded-r-lg' }}"></div>

                            <div class="prose prose-sm dark:prose-invert max-w-none relative z-10 {{ $side == 'right' ? 'md:ml-2' : 'md:mr-2' }}">
                                {!! $item['content'] ?? '' !!}
                            </div>

                            @if (isset($item['tags']) && is_array($item['tags']) && count($item['tags']) > 0)
                                <div class="mt-4 flex flex-wrap gap-1.5 {{ $side == 'left' ? 'md:justify-end' : '' }} relative z-10">
                                    @foreach ($item['tags'] as $tag)
                                        <span class="px-2.5 py-0.5 text-xs font-medium bg-{{ $color }}-100/80 dark:bg-{{ $color }}-900/30 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-md transition-colors duration-300 hover:bg-{{ $color }}-200 dark:hover:bg-{{ $color }}-800/40">
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
                            <span class="inline-block px-3 py-1 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/20 text-{{ $color }}-800 dark:text-{{ $color }}-300 rounded-md font-semibold text-sm shadow-sm">
                                {{ $item['date'] ?? '' }}
                            </span>
                            <div class="mt-2 text-lg font-bold bg-gradient-to-br from-{{ $color }}-700 to-{{ $color }}-900 dark:from-{{ $color }}-300 dark:to-{{ $color }}-500 text-transparent bg-clip-text">{{ $item['title'] ?? '' }}</div>
                            <div class="text-gray-600 dark:text-gray-400">{{ $item['subtitle'] ?? '' }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        /* Timeline animations */
        .timeline-pulse {
            animation: timeline-pulse 3s infinite;
        }

        @keyframes timeline-pulse {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        /* Timeline item reveal animation */
        .animate-timeline-item {
            animation: timeline-item-reveal 0.6s ease-out forwards;
            animation-delay: var(--delay, 0s);
        }

        @keyframes timeline-item-reveal {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</div>