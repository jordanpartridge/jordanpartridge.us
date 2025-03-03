@props(['limit' => 4, 'columns' => 2])

@php
$repositories = App\Models\GithubRepository::where('featured', true)
    ->where('is_active', true)
    ->orderBy('display_order')
    ->limit($limit)
    ->get();
@endphp

<div class="grid grid-cols-1 md:grid-cols-{{ $columns }} gap-8">
    @forelse ($repositories as $repository)
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 group">
            <div class="h-48 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 flex items-center justify-center">
                <i class="fab fa-github text-5xl text-gray-700 dark:text-gray-300 group-hover:scale-110 transition-transform duration-300"></i>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ $repository->name }}</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $repository->description }}</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach ($repository->technologies ?? [] as $tech)
                        <span class="text-xs font-medium py-1 px-2 rounded-full bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300">{{ $tech }}</span>
                    @endforeach
                    @if ($repository->stars_count > 0)
                        <span class="text-xs font-medium py-1 px-2 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300">
                            <i class="fas fa-star mr-1"></i>{{ $repository->stars_count }}
                        </span>
                    @endif
                    @if ($repository->forks_count > 0)
                        <span class="text-xs font-medium py-1 px-2 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">
                            <i class="fas fa-code-branch mr-1"></i>{{ $repository->forks_count }}
                        </span>
                    @endif
                </div>
                <a href="{{ $repository->url }}" target="_blank" class="inline-flex items-center font-medium text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                    View Repository
                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    @empty
        <!-- Fallback if no featured repositories -->
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 group col-span-{{ $columns }}">
            <div class="p-6 text-center">
                <i class="fab fa-github text-5xl text-gray-400 dark:text-gray-500 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">No Featured Projects Yet</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">Check back soon for featured GitHub projects, or visit my GitHub profile to see all my repositories.</p>
                <a href="https://github.com/jordanpartridge" target="_blank" class="inline-flex items-center font-medium text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                    Visit GitHub Profile
                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    @endforelse
</div>