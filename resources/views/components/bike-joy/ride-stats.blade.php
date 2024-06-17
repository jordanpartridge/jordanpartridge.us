@props(['ride', 'condense' => false])

<div class="{{ $condense ? 'w-full parallax flex flex-col justify-center items-center' : 'lg:w-2/3 flex flex-col justify-between' }}">
    <div class="text-center mb-2">
        <div class="flex flex-col items-center space-y-2">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white ">{{ $ride->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center space-x-2">
                <span class="text-lg text-blue-500">{{ $ride->icon }}</span>
                <span>{{ $ride->rideDiff }}</span>
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center space-x-2">
                <span class="text-lg text-green-500">📏</span>
                <span>{{ $ride->distance . ' miles' }}</span>
            </p>
        </div>
    </div>

    @unless ($condense)
        <div class="mt-4 space-y-2">
            @foreach([
                        'Distance' => [$ride->distance . ' miles', '🚴'],
                        'Duration' => [$ride->moving_time, '⏱️'],
                        'Elevation' => [$ride->elevation . ' ft', '🏔️'],
                        'Calories Burned' => [$ride->calories . ' kcal', '🔥'],
                        'Avg Speed' => [$ride->average_speed . ' mph', '⚡'],
                        'Max Speed' => [$ride->max_speed . ' mph', '🏎️'],
                    ] as $label => [$value, $icon])
                <x-bike-joy.ride-stat :icon="$icon" :label="$label" :value="$value"/>
            @endforeach
        </div>
    @endunless
</div>
<style>
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .parallax {
        background-image: url('https://img.freepik.com/free-vector/mountains-cleft-view-from-bottom-night-scenery-landscape-with-high-rocks-full-moon-with-stars-glowing-peaks_107791-5585.jpg?size=626&ext=jpg');
        min-height: 400px;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    html {
        scroll-behavior: smooth;
    }

    .animate-bounce {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(-25%);
            animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
        }
        50% {
            transform: translateY(0);
            animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
        }
    }

    .ride-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .ride-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
</style>
