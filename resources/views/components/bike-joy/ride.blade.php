<div class="grid sm:grid-cols-1 md:grid-cols-2 gap-4">
  <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
    <x-route-map :ride="$ride"></x-route-map>
  </div>
  <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
    <div class="mt-4">
      <p class="text-slate-800 dark:text-white dark:bg-slate-500 mb-2 hover:bg-gray-200 p-2 rounded">Title: {{$ride->name}}</p>
      <p class="text-slate-800 dark:text-white dark:bg-slate-500 mb-2 hover:bg-gray-200 p-2 rounded">Date: {{$ride->date}}</p>
      <p class="text-slate-800 dark:text-white dark:bg-slate-500 mb-2 hover:bg-gray-200 p-2 rounded">Distance: {{$ride->distance}}</p>
      <p class="text-slate-800 dark:text-white dark:bg-slate-500 mb-2 hover:bg-gray-200 p-2 rounded">Duration: {{$ride->moving_time}}</p>
      <p class="text-slate-800 dark:text-white dark:bg-slate-500 mb-2 hover:bg-gray-200 p-2 rounded">Elevation Gain: {{$ride->elevation}}</p>
      <p class="text-slate-800 dark:text-white dark:bg-slate-500 mb-2 hover:bg-gray-200 p-2 rounded">Average Speed: {{$ride->average_speed}}</p>
      <p class="text-slate-800 dark:text-white dark:bg-slate-500 mb-2 hover:bg-gray-200 p-2 rounded">Max Speed: {{$ride->max_speed}}</p>
    </div>
  </div>
</div>
