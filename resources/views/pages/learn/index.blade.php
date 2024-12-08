@php
    use function Laravel\Folio\name;
    use function Laravel\Folio\{middleware};

    name('learn.index');
    middleware(['web']);
@endphp

@volt
<div>
    <x-app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h1 class="text-2xl font-semibold mb-6">Learning Resources</h1>
                    <p class="text-gray-600 dark:text-gray-300">Learning resources coming soon! Check back later for tutorials and guides.</p>
                </div>
            </div>
        </div>
    </x-app-layout>
</div>
@endvolt