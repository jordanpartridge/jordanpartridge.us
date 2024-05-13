<div class="grid grid-cols-1 sm:grid-cols-2 md:gap-4 xs:grids-cols-2 mb-6">
    <div class="md:p-6">
        <label class="block text-slate-800 dark:text-gray-200" for="startDate">Start Date</label>
        <input aria-label="start date"
               class="text-gray-700 bg-white dark:text-gray-200 dark:bg-gray-800" type="date"
               id="startDate" name="startDate" wire:model="startDate"
               wire:change="updateRange">
    </div>
    <div class="md:p-6">
        <label class="block text-slate-800 dark:text-gray-200" for="endDate">End Date</label>
        <input aria-label="end date"
               class="text-gray-700 bg-white dark:text-gray-200 dark:bg-gray-800" type="date"
               id="endDate" name="endDate" wire:model="endDate"
               wire:change="updateRange">
    </div>
</div>
