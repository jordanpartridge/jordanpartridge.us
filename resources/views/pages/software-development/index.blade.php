@php
    use function Laravel\Folio\name;
    use function Laravel\Folio\{middleware};

    name('software-development.index');
    middleware(['web']);
@endphp

@volt
<?php

$projects = []; // This should come from your data source

?>

<div>
    <x-app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h1 class="text-2xl font-semibold mb-6">Software Development Projects</h1>

                    @if (count($projects) > 0)
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            @foreach ($projects as $project)
                                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm border dark:border-gray-600">
                                    <h2 class="text-xl font-medium mb-2">{{ $project->title }}</h2>
                                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $project->excerpt }}</p>
                                    <a href="/software-development/{{ $project->slug }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                                        View Project →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-300">No projects available yet. Check back soon!</p>
                    @endif
                </div>
            </div>
        </div>
    </x-app-layout>
</div>
@endvolt