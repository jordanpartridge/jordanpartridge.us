@props([
    'icon' => '',
    'title' => '',
    'value' => '',
    'unit' => '',
])

<div class="fat-bike-gradient rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 camo-border">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-semibold text-white military-font">
                <span class="emoji">{{ $icon }}</span> {{ $title }}
            </h3>
            <p class="text-white text-lg special-text">{{ $value }} {{ $unit }}</p>
        </div>
    </div>
</div>