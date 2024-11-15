<?php

use App\Models\Badge;

use function Livewire\Volt\state;

state(['badges' => fn () => Badge::getActiveBadges()->toArray()]);

?>
@volt('marquee')
<div
    x-data="{
        badges: @js($badges),
        currentIndex: 0,
        show: true,
        direction: 'next',
    }"
    x-init="setInterval(() => {
        show = false;
        setTimeout(() => {
            currentIndex = (currentIndex + 1) % badges.length;
            show = true;
        }, 750);
    }, 3000)"
    class="fixed top-4 left-1/2 transform -translate-x-1/2 z-10 w-full max-w-[90vw] md:max-w-[200px]"
>
    <div class="overflow-hidden">
        <div
            x-show="show"
            class="inline-block whitespace-nowrap"
            x-transition:enter="transition-all duration-500"
            x-transition:enter-start="opacity-0 -translate-x-full"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition-all duration-500"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-full"
        >
            <span x-text="badges[currentIndex].icon" class="mr-2"></span>
            <span x-text="badges[currentIndex].title"></span>
        </div>
    </div>

</div>
@endvolt
