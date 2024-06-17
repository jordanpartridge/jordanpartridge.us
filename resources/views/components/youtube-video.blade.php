<div class="relative flex flex-col items-center justify-center w-full h-auto overflow-hidden m-6"
     x-cloak>
    <div
        class="flex flex-col items-center justify-center w-full px-8 pt-12 pb-20 bg-black rounded-lg shadow-xl transform transition-transform duration-500 hover:scale-105">
        <div class="container max-w-6xl mx-auto text-center">
            <h2 class="text-3xl font-semibold text-white mb-4">Podcast Highlight</h2>
            <div class="max-w-4xl mx-auto mt-10">
                <div class="dark:bg-slate-900 bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                    <div class="relative pb-[56.25%]">
                        <iframe class="absolute inset-0 w-full h-full"
                                src="{{$url}}"
                                allowfullscreen></iframe>
                    </div>
                    <div class="p-6 dark:text-white bg-gray-900">
                        <h2 class="text-2xl font-bold mb-2">{{$title}}</h2>
                        <p class="text-base text-gray-300 mt-2">
                            {{$description}}
                        </p>
                        <div class="mt-4">
                            <x-youtube-subscribe-button channelId="UC8K4ImiCmXKQO6Lb5A8QMcg"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center mt-8">
        <a href="#recent-rides" class="animate-bounce">
            <svg class="h-8 w-8 text-gray-800 dark:text-white" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7"/>
            </svg>
        </a>
    </div>
</div>
