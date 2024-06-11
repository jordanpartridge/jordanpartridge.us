<div class="relative flex flex-col items-center justify-center w-full h-auto overflow-hidden m-6"
     x-cloak>
    <div class="flex flex-col items-center justify-center w-full px-8 pt-12 pb-20">
        <div class="container max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-semibold text-slate-800 dark:text-white mb-4">Podcast Highlight</h2>
            <div class="max-w-sm mx-auto mt-10">
                <div class="dark:bg-slate bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="relative pb-56">
                        <iframe class="absolute inset-0 w-full h-full"
                                src="{{$url}}" frameborder="0"
                                allowfullscreen></iframe>
                    </div>
                    <div class="p-4 dark:text-white dark:bg-slate-900 bg-slate-100 rounded-b-lg">
                        <h2 class="text-xl font-semibold">{{$title}}</h2>
                        <p class="dark:text-sky-100 text-gray-600 mt-2">
                            {{$description}}
                        </p>
                        <div class="m-6">
                            <x-youtube-subscribe-button channelId="UC8K4ImiCmXKQO6Lb5A8QMcg"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
