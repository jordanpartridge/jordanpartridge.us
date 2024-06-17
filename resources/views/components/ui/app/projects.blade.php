<div class="container mx-auto px-4 mt-10">
    <h2 class="text-3xl font-semibold text-slate-800 dark:text-white mb-4">My Projects</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ([
            ['title' => 'My Career Advisor', 'description' => 'A no cost career services platform powered by Goodwill', 'url' => 'https://www.mycareeradvisor.com'],
        ] as $project)
            <div
                class="flex flex-col items-center justify-center w-full p-8 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-xl">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{$project['title']}}</h3>
                <p class="mt-2 text-base text-gray-800 dark:text-white">{{$project['description']}}</p>
                <a href="{{$project['url']}}" target="_blank"
                   class="mt-4 text-blue-600 hover:underline">View Project</a>
            </div>
        @endforeach
    </div>
</div>
