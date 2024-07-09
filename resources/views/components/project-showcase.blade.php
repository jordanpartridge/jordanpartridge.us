<!-- resources/views/components/project-showcase.blade.php -->
@props(['projects'])

<div class="space-y-16">
    <h2 class="text-4xl font-extrabold text-gray-800 dark:text-white mb-12 text-center">{{ $slot }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        @foreach($projects as $project)
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden transition duration-300 hover:shadow-2xl transform hover:-translate-y-2">
                <div class="{{ $project['headerClass'] }} p-6 flex justify-center items-center">
                    @if($project['logo'])
                        <img class="h-16 object-contain" src="{{ $project['logo'] }}" alt="{{ $project['name'] }}">
                    @else
                        <x-ui.avatar class="mx-auto mb-6 w-40 h-40 border-4 border-blue-500 shadow-lg rounded-full transition-transform duration-300 hover:scale-105"/>
                    @endif
                    @if($project['headerContent'])
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $project['headerContent']['title'] }}</h3>
                            <p class="text-xl font-bold text-white">{{ $project['headerContent']['subtitle'] }}</p>
                        </div>
                    @endif
                </div>
                <div class="p-8">
                    <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">{{ $project['category'] }}</div>
                    <a href="{{ $project['url'] }}" target="_blank" class="block mt-1 text-2xl leading-tight font-bold text-gray-900 dark:text-white hover:underline">{{ $project['name'] }}</a>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $project['description'] }}</p>
                    @if(isset($project['subProjects']))
                        @foreach($project['subProjects'] as $subProject)
                            <a href="{{ $subProject['url'] }}" class="block mt-1 text-2xl leading-tight font-bold text-gray-900 dark:text-white hover:underline">{{ $subProject['name'] }}</a>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $subProject['description'] }}</p>
                        @endforeach
                    @endif
                    <div class="mt-4 space-y-2">
                        @if(isset($project['contributions']))
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Key Contributions:</h4>
                            <ul class="space-y-2">
                                @foreach($project['contributions'] as $contribution)
                                    <li class="flex items-center">
                                        <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-300">{{ $contribution }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if(isset($project['technologies']))
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Technologies Used:</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($project['technologies'] as $tech)
                                    <span class="px-2 py-1 bg-{{ $tech['color'] }}-100 text-{{ $tech['color'] }}-800 rounded-full text-sm">{{ $tech['name'] }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
