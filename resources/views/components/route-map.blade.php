@props(['ride', 'condense' => false])

<div class="{{ $condense ? 'relative w-full mb-4' : 'relative lg:w-1/3 mb-4 lg:mb-0 lg:mr-6' }}">
    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden w-full h-64 lg:h-full shadow-lg
                transition-all duration-300 hover:shadow-xl relative group">
        <!-- Subtle gradient overlay for depth -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-60 z-10"></div>

        <img
            src="{{ $ride->mapUrlSigned }}"
            alt="Route Map for {{ $ride->name }}"
            class="w-full h-full object-cover transition-all duration-500 group-hover:scale-[1.02]">

        <!-- Premium view indicator -->
        <div class="absolute top-3 right-3 bg-black/80 backdrop-blur-md p-1.5 rounded-xl
                    opacity-0 group-hover:opacity-100 transition-all duration-500 border border-white/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </div>

        <!-- Refined stats overlay -->
        <div class="absolute bottom-0 left-0 right-0 bg-black/75 backdrop-blur-md
                    transform translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out
                    border-t border-white/10">
            <div class="p-4 space-y-2">
                <!-- Primary stats with accents -->
                <div class="flex justify-between items-center">
                    <span class="flex items-center gap-2">
                        <span class="h-6 w-6 rounded-full bg-red-500/20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </span>
                        <span class="text-sm font-medium text-white">
                            {{ $ride->distance }} <span class="text-white/60">mi</span>
                        </span>
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="h-6 w-6 rounded-full bg-blue-500/20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </span>
                        <span class="text-sm font-medium text-white">
                            {{ $ride->average_speed }} <span class="text-white/60">mph</span>
                        </span>
                    </span>
                </div>

                <!-- Secondary stats -->
                <div class="flex justify-between items-center border-t border-white/10 pt-2">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                        </svg>
                        <span class="text-xs font-medium text-white/60">
                            {{ $ride->elevation }} <span class="text-white/40">ft</span>
                        </span>
                    </span>
                    @if ($ride->moving_time)
                        <span class="text-xs font-medium text-white/60">
{{--                            /.{{ sprintf("%02d:%02d", floor($ride->moving_time / 3600), floor(($ride->moving_time / 60) % 60)) }}--}}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
