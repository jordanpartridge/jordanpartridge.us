@props([
    'title' => 'THE FAT BIKER\'S CREED',
    'content' => "This is my fat bike. There are many like it, but this one is mine.\nMy fat bike is my best friend. It is my life.\nWithout me, my fat bike is useless. Without my fat bike, I am without joy.\nI will ride my fat bike true. I will conquer snow, sand, and mud that hinders other bikes.\nI will..."
])

<div class="mt-12 p-6 bg-gray-800 text-white rounded-lg camo-border creed-container">
    <div class="creed-background"></div>
    <div class="creed-content">
        <h3 class="text-2xl font-bold mb-4 military-font military-section-header">{{ $title }}</h3>
        <p class="italic text-lg">
            {!! nl2br(e($content)) !!}
        </p>
    </div>
</div>