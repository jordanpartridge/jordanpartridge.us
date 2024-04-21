<article class="dark:bg-gray-900 bg-slate-300 p-4 shadow rounded-lg  min-h-[280px]">
    <h2 class="mb-4 text-2xl text-blue-900 font-bold font-serif">
        <a href="#" class="transition-colors duration-200 hover:underline hover:text-black dark:text-amber-50"> {{ \Illuminate\Support\Str::limit($ride->name, 20, '...') }}
        </a>
    </h2>
    <div class="flex flex-row items-start">
        <x-route-map :ride="$ride" class=" w-full h-64 sm:w-72 sm:h-72 md:w-96 md:h-96 "  x-show="layout === 'list'"/>
        <div class=" justify-between text-xl font-light ml-4 w-full" x-cloak   >
            <table class="table-auto w-full" x-show="layout === 'list'">
                <tbody>
                    <tr>
                        <td class="border px-4 py-2 font-semibold bg-blue-300">Distance:</td>
                        <td class="border px-4 py-2 bg-slate-300">{{ $ride->distance }} Miles</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2 font-semibold bg-blue-300">Max Speed:</td>
                        <td class="border px-4 py-2 bg-slate-300">{{ $ride->max_speed }} MPH</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2 font-semibold bg-blue-300">Average Speed</td>
                        <td class="border px-4 py-2 bg-slate-300">{{ $ride->average_speed}} MPH</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2 font-semibold bg-blue-300">Date</td>
                        <td class="border px-4 py-2 bg-slate-300">{{ $ride->date }} </td>
                </tbody>
            </table>
            <ul class="mt-4" x-show="layout === 'grid'">
                <li class="flex items-center justify-start">
                    <svg class="w-6 h-6 mr-2 text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-gray-600 dark:text-gray-300">{{ $ride->distance }} Miles</span>
                </li>
                <li class="flex items
            <p class="text-right mt-4">
                <a href="#"
                   class="uppercase font-semibold text-base text-gray-600 hover:text-black">
                    {{ \Carbon\Carbon::create($ride->date)->format('M d, Y h:i A') }}
                </a>
            </p>
        </div>
    </div>
</article>
