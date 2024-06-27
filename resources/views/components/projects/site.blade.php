<div x-data="{
    commandHistory: [],
    currentInput: '',
    activeWindows: [],
    autocompletePreview: '',
    isMinimized: false,
    siteInfo: {
        // ... (keep the existing siteInfo object)
    },
    commands: ['about', 'tech', 'features', 'api', 'hosting', 'source', 'open', 'close', 'clear', 'help', 'ls', 'cat', 'contact'],
    executeCommand(cmd) {
        // ... (keep the existing executeCommand function)
        this.$nextTick(() => {
            this.scrollToBottom();
        });
    },
    autocomplete() {
        // ... (keep the existing autocomplete function)
    },
    scrollToBottom() {
        const terminal = this.$refs.terminal;
        terminal.scrollTop = terminal.scrollHeight;
    },
    toggleMinimize() {
        this.isMinimized = !this.isMinimized;
    }
}"
     class="fixed bottom-4 right-4 w-96 bg-gray-900 text-green-500 rounded-lg shadow-2xl overflow-hidden transition-all duration-300 ease-in-out"
     :class="{ 'h-12': isMinimized, 'h-[32rem]': !isMinimized }"
     @keydown.ctrl.l.window="commandHistory = []"
     @keydown.tab.prevent="if (autocompletePreview) { currentInput = autocompletePreview; autocompletePreview = ''; }"
>
    <div class="bg-green-900 px-4 py-2 flex justify-between items-center cursor-move" @mousedown="$event.preventDefault()">
        <h3 class="text-lg font-bold text-green-100">JP Terminal</h3>
        <div>
            <button @click="toggleMinimize" class="text-green-100 hover:text-white mr-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <button @click="$root.remove()" class="text-green-100 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <div x-show="!isMinimized" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-4">
        <div class="p-4">
            <p class="text-green-300 text-sm mb-2">Welcome to JP Terminal. Type 'help' for available commands.</p>
        </div>

        <div x-ref="terminal" class="bg-black bg-opacity-50 p-4 h-64 overflow-y-auto mb-4 text-sm font-mono">
            <template x-for="(item, index) in commandHistory" :key="index">
                <div :class="{'mb-2': true, 'text-green-400': item.type === 'input', 'text-green-200': item.type === 'output'}" class="text-right">
                    <template x-if="item.type === 'input'">
                        <span class="mr-2">$</span>
                    </template>
                    <pre x-text="item.content" class="whitespace-pre-wrap inline-block text-left"></pre>
                </div>
            </template>
        </div>

        <div class="px-4 pb-4">
            <div class="flex bg-black bg-opacity-50 rounded overflow-hidden relative">
                <span class="px-3 py-2 text-green-400 bg-green-900 bg-opacity-50">$</span>
                <div class="relative flex-grow">
                    <input
                        x-model="currentInput"
                        @keydown.enter="executeCommand(currentInput); currentInput = ''; autocompletePreview = ''"
                        @input="autocomplete"
                        type="text"
                        placeholder="Type a command..."
                        class="w-full px-3 py-2 bg-transparent border-none outline-none text-green-300 font-mono"
                        autofocus
                    >
                    <div x-show="autocompletePreview" class="absolute top-0 left-0 w-full h-full pointer-events-none">
                        <span class="px-3 py-2 text-green-600" x-text="currentInput"></span>
                        <span class="px-3 py-2 text-green-600" x-text="autocompletePreview.slice(currentInput.length)"></span>
                        <span class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs text-green-600">Tab to complete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fancy Tech Modals -->
    <div class="fixed inset-0 flex items-center justify-center z-50" x-show="activeWindows.length > 0" style="display: none;">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative w-full max-w-4xl">
            <template x-for="window in activeWindows" :key="window.title">
                <div class="bg-gray-900 rounded-lg shadow-2xl overflow-hidden m-4 border border-green-500">
                    <div class="bg-green-900 px-4 py-2 flex justify-between items-center">
                        <h4 x-text="window.title" class="text-green-300 font-bold uppercase"></h4>
                        <button @click="activeWindows = activeWindows.filter(w => w.title !== window.title)" class="text-green-300 hover:text-green-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <iframe :src="window.url" class="w-full h-[calc(100vh-200px)] border-none"></iframe>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script>
    interact('.cursor-move')
        .draggable({
            listeners: {
                move (event) {
                    var target = event.target.parentElement
                    var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx
                    var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy

                    target.style.transform = `translate(${x}px, ${y}px)`

                    target.setAttribute('data-x', x)
                    target.setAttribute('data-y', y)
                }
            }
        })
</script>
