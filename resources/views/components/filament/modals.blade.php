@props(['align' => 'center', 'width' => 'md', 'height' => null])

<div
    x-data="{
        isOpen: false,
        modalData: {},
        open(data = {}) {
            this.modalData = data;
            this.isOpen = true;
            document.body.classList.add('overflow-hidden');
        },
        close() {
            this.isOpen = false;
            document.body.classList.remove('overflow-hidden');
        },
    }"
    x-on:open-modal.window="open($event.detail || {})"
    x-on:close-modal.window="close()"
    x-on:keydown.escape.window="close()"
    class="fixed inset-0 z-50 flex items-center justify-center"
    x-show="isOpen"
    x-cloak
>
    <div
        x-show="isOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 transition-all bg-gray-900 bg-opacity-50 dark:bg-opacity-70"
        x-on:click="close()"
    ></div>

    <div
        x-show="isOpen"
        x-trap.inert.noscroll="isOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-h-[90vh] overflow-auto p-4 sm:p-6 bg-white dark:bg-gray-800 rounded-xl shadow-xl transition-all sm:max-w-2xl"
        x-on:click.outside="close()"
    >
        <div class="absolute top-3 right-3">
            <button
                x-on:click="close()"
                type="button"
                class="flex items-center justify-center text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300"
            >
                <span class="sr-only">Close</span>
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-4">
            {{ $slot }}
        </div>
    </div>
</div>
