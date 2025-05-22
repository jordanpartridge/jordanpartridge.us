@props([
    'startDate' => '',
    'endDate' => '',
])

<div class="grid grid-cols-1 sm:grid-cols-2 md:gap-4 xs:grids-cols-2 mb-6">
    <div class="md:p-6">
        <label class="block text-slate-800 dark:text-gray-200 mb-2 military-font" for="startDate">
            MISSION START DATE</label>
        <input aria-label="start date"
               class="text-gray-700 bg-white dark:text-gray-200 dark:bg-gray-800 rounded-lg p-3 w-full mb-4 border-2 border-gray-300 dark:border-gray-600 focus:border-green-500 dark:focus:border-green-500 focus:outline-none transition-colors duration-300"
               type="date" id="startDate" name="startDate" wire:model="startDate"
               wire:change="recalculateMetrics">
    </div>
    <div class="md:p-6">
        <label class="block text-slate-800 dark:text-gray-200 mb-2 military-font" for="endOfWeek">
            MISSION END DATE</label>
        <input aria-label="end date"
               class="text-gray-700 bg-white dark:text-gray-200 dark:bg-gray-800 rounded-lg p-3 w-full mb-4 border-2 border-gray-300 dark:border-gray-600 focus:border-green-500 dark:focus:border-green-500 focus:outline-none transition-colors duration-300"
               type="date" id="endOfWeek" name="endOfWeek" wire:model="endDate"
               wire:change="recalculateMetrics">
    </div>
</div>