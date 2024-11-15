<div id="badge">
    <div x-data="{
        badges: [
        { title: 'Engineering Manager', icon: '👨‍💻' },
        { title: 'Laravel Developer', icon: '🚀' },
        { title: 'Army Veteran', icon: '🎖️'},
        { title: 'Cycling Enthusiast', icon: '🚴'}
    ],
    currentIndex: 0,
    show: true,
    direction: 'next'
}"
         x-init="setInterval(() => {
                show = false;
                setTimeout(() => {
                    currentIndex = (currentIndex + 1) % badges.length;
                    show = true;
                }, 750);
            }, 3000)"
         class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 z-10 w-full min-w-[200px]"
    >
        <div class="mt-3 flex flex-col items-center gap-2">
            <div class="text-sm font-medium text-gray-400">
                <span x-text="badges[currentIndex].icon" class="mr-2"></span>
                <span x-text="badges[currentIndex].title"
                      x-transition:enter="transition-all duration-500"
                      x-transition:enter-start="opacity-0 translate-y-2"
                      x-transition:enter-end="opacity-100 translate-y-0"></span>
            </div>
        </div>
    </div>
</div>
