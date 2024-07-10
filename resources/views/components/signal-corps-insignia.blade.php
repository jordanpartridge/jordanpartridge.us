<div x-data="{
    clicks: 0,
    showVideo: false,
    player: null,
    incrementClicks() {
        this.clicks++;
        if (this.clicks === 25) {
            this.showVideo = true;
            this.$nextTick(() => {
                this.initializeYouTubePlayer();
            });
        }
    },
    closeVideo() {
        this.showVideo = false;
        this.clicks = 0;
        if (this.player) {
            this.player.destroy();
            this.player = null;
        }
    },
    initializeYouTubePlayer() {
        if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
            window.onYouTubeIframeAPIReady = this.loadPlayer;
            const tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            const firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        } else {
            this.loadPlayer();
        }
    },
    loadPlayer() {
        this.player = new YT.Player('youtubePlayer', {
            videoId: 'zr8Ufv3QJhY',
            playerVars: {
                'autoplay': 1,
                'controls': 0,
                'rel': 0,
                'showinfo': 0
            },
            events: {
                'onStateChange': this.onPlayerStateChange
            }
        });
    },
    onPlayerStateChange(event) {
        if (event.data === YT.PlayerState.ENDED) {
            this.closeVideo();
        }
    }
}" class="flex flex-col items-center justify-center text-center space-y-4 mb-8">
    <div class="relative inline-block" :class="{ 'animate-glow': showVideo }">
        <img @click="incrementClicks" src="img/Signal Corps Insignia.png" alt="U.S. Army Signal Corps Insignia" class="w-32 h-32 drop-shadow-[0_0_0.75rem_rgba(0,0,0,0.5)] transition-all duration-300 hover:scale-105 cursor-pointer">
    </div>
    <div class="space-y-2">
        <p class="text-2xl italic font-semibold text-yellow-600 dark:text-yellow-400 drop-shadow-[0_2px_2px_rgba(0,0,0,0.3)] transition-colors duration-300">
            "Pro Patria Vigilans"
        </p>
        <p class="text-lg text-blue-600 dark:text-blue-300 transition-colors duration-300">
            Watchful for the Country
        </p>
    </div>

    <!-- YouTube Modal -->
    <div x-show="showVideo"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center">

        <!-- Semi-transparent overlay -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Video Container -->
        <div class="relative w-full max-w-5xl h-4/5 rounded-3xl overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.3)] z-10">
            <button @click="closeVideo" class="absolute top-6 right-6 text-white hover:text-gray-300 z-20 transition-all duration-300 hover:scale-110 hover:rotate-90">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="absolute inset-0 border-fade">
                <div id="youtubePlayer" class="w-full h-full opacity-60"></div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes glow {
        0%, 100% { filter: drop-shadow(0 0 5px rgba(255, 223, 0, 0.7)); }
        50% { filter: drop-shadow(0 0 20px rgba(255, 223, 0, 0.9)); }
    }
    .animate-glow {
        animation: glow 2s ease-in-out infinite;
    }
    .border-fade {
        -webkit-mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%),
        linear-gradient(to bottom, transparent 0%, black 5%, black 95%, transparent 100%);
        mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%),
        linear-gradient(to bottom, transparent 0%, black 5%, black 95%, transparent 100%);
        -webkit-mask-size: 100% 100%;
        mask-size: 100% 100%;
        -webkit-mask-repeat: no-repeat;
        mask-repeat: no-repeat;
        -webkit-mask-position: center;
        mask-position: center;
    }
</style>
