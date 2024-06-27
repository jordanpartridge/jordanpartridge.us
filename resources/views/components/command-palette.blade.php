<div x-data="commandPalette()"
     x-show="isVisible"
     @keydown.window.prevent.cmd.k="togglePalette()"
     @keydown.window.prevent.ctrl.k="togglePalette()"
     @keydown.window.escape="hidePalette()"
     class="fixed inset-0 z-50 overflow-y-auto bg-purple-400"
     aria-labelledby="modal-title"
     x-ref="dialog"
     aria-modal="true">

    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="isVisible"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="isVisible"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                           Jordan Palette  - your helpful palette of commands
                        </h3>
                        <div class="mt-2">
                            <input x-model="input"
                                   @input="search"
                                   @keydown.enter="executeCommand"
                                   @keydown.arrow-up.prevent="navigateResults('up')"
                                   @keydown.arrow-down.prevent="navigateResults('down')"
                                   type="text"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Type a command or search...">
                        </div>
                        <div class="mt-2 max-h-60 overflow-y-auto">
                            <template x-for="(result, index) in filteredResults" :key="index">
                                <div @click="executeCommand(result)"
                                     :class="{'bg-indigo-100': index === selectedIndex}"
                                     class="px-3 py-2 hover:bg-gray-100 cursor-pointer">
                                    <span x-text="result.name"></span>
                                    <span x-text="result.description" class="text-sm text-gray-500 ml-2"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function commandPalette() {
            return {
                isVisible: false,
                input: '',
                commands: [
                    { name: 'About', description: 'Learn about the site', action: () => { /* Navigate to about page */ } },
                    { name: 'Blog', description: 'Read latest posts', action: () => { /* Navigate to blog */ } },
                    { name: 'Portfolio', description: 'View my work', action: () => { /* Navigate to portfolio */ } },
                    { name: 'Contact', description: 'Get in touch', action: () => { /* Navigate to contact page */ } },
                    { name: 'Theme: Light', description: 'Switch to light mode', action: () => { /* Switch theme */ } },
                    { name: 'Theme: Dark', description: 'Switch to dark mode', action: () => { /* Switch theme */ } },
                ],
                filteredResults: [],
                selectedIndex: 0,

                togglePalette() {
                    this.isVisible = !this.isVisible;
                    if (this.isVisible) {
                        this.$nextTick(() => {
                            this.$refs.dialog.querySelector('input').focus();
                        });
                    }
                    this.input = '';
                    this.search();
                },

                hidePalette() {
                    this.isVisible = false;
                },

                search() {
                    this.filteredResults = this.commands.filter(command =>
                        command.name.toLowerCase().includes(this.input.toLowerCase()) ||
                        command.description.toLowerCase().includes(this.input.toLowerCase())
                    );
                    this.selectedIndex = 0;
                },

                navigateResults(direction) {
                    if (direction === 'up' && this.selectedIndex > 0) {
                        this.selectedIndex--;
                    } else if (direction === 'down' && this.selectedIndex < this.filteredResults.length - 1) {
                        this.selectedIndex++;
                    }
                },

                executeCommand(command) {
                    if (command) {
                        command.action();
                    } else if (this.filteredResults.length > 0) {
                        this.filteredResults[this.selectedIndex].action();
                    }
                    this.hidePalette();
                }
            }
        }
    </script>
@endpush
