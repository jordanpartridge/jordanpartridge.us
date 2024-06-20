<!-- Recent Rides Section -->
<div id="recent-rides" class="container mx-auto px-4 mt-10">
    <h2 class="text-3xl font-semibold text-slate-800 dark:text-white mb-4">Recent Rides</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach (\App\Models\Ride::take(6)->get() as $ride)
            <div
                class="flex flex-col items-center justify-center w-full p-6 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-xl transform transition-transform duration-300 hover:scale-105">
                <x-bike-joy.ride :ride="$ride" :condense="true">
                    <div class="ride-title">
                        {{ $ride->name }}
                    </div>
                    <div class="ride-details">
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            Distance: {{ $ride->distance }} km
                        </p>
                        <p class="text-gray-600 dark:text-gray-400">
                            Duration: {{ $ride->duration }} hrs
                        </p>
                        <p class="text-gray-600 dark:text-gray-400">
                            Location: {{ $ride->location }}
                        </p>
                    </div>
                </x-bike-joy.ride>
            </div>
        @endforeach
    </div>
    <div class="mt-8 flex justify-center">
        <a href="/bike"
           class="relative inline-block px-8 py-3 bg-gradient-to-r from-blue-500 to-green-500 text-white rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-110 hover:shadow-2xl hover:from-green-500 hover:to-blue-500">
        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v1a3 3 0 01-6 0v-1a3 3 0 013-3h1.5a2.5 2.5 0 000-5H3m13-4h.01M9 11V9a3 3 0 013-3h1.5a2.5 2.5 0 000-5H15a3 3 0 013 3v1a3 3 0 003 3h-1.5a2.5 2.5 0 000 5H21M9 11h6"/>
            </svg>
        </span>
            <span>View More Bike Adventures</span>
        </a>
    </div>
</div>

<style>
    .ride-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #1a202c; /* dark:text-white for dark mode */
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
        transition: all 0.3s ease-in-out;
    }

    .ride-title:hover {
        overflow: visible;
        white-space: normal;
        text-overflow: clip;
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 0.5rem;
        border-radius: 0.5rem;
        position: relative;
        z-index: 10;
    }

    .ride-details {
        text-align: center;
        margin-top: 0.5rem;
        font-size: 1rem;
        color: #4a5568; /* dark:text-gray-400 for dark mode */
    }
</style>
