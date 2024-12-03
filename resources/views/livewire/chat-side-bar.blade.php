<div class="relative w-full">
    <!-- Minimized Button -->
    <div class="fixed bottom-0 {{ $winPosition == 'left' ? 'left-0' : 'right-0' }} z-30 cursor-pointer border-gray-500 shadow">
        <div
            class="relative h-6 w-6 p-1 rounded-full text-white flex items-center justify-center"
            style="background-color: {{ $panelHidden ? '#888' : 'rgb(16, 163, 127)' }};"
            wire:click="$toggle('panelHidden')"
        >
            <x-heroicon-o-chat-bubble-left-right class="w-4 h-4" />
        </div>
    </div>

    <!-- Chat Window -->
    <div
        class="flex-1 p-2 sm:p-6 justify-between flex flex-col h-screen fixed {{ $winPosition == 'left' ? 'left-0' : 'right-0' }} bottom-0 bg-white shadow z-30 {{ $panelHidden ? 'hidden' : '' }}"
        style="{{ $winWidth }}"
    >
        <!-- Header -->
        <div class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200">
            <div class="relative flex items-center space-x-4">
                <div class="relative">
                    <span class="absolute text-green-500 right-0 bottom-0">
                        <svg width="20" height="20">
                            <circle cx="8" cy="8" r="8" fill="currentColor"></circle>
                        </svg>
                    </span>
                    <div class="relative h-[30px] w-[30px] p-1 rounded-sm text-white flex items-center justify-center" style="background-color: rgb(16, 163, 127);">
                    </div>
                </div>
                <div class="flex flex-col leading-tight">
                    <div class="text-2xl mt-1 flex items-center">
                        <span class="text-gray-700 mr-3">{{ $name }}</span>
                    </div>
                </div>
            </div>

            <!-- Control Buttons -->
            <div class="flex items-center space-x-2">
                <button
                    type="button"
                    wire:click="resetSession"
                    class="inline-flex items-center justify-center rounded-lg border h-8 w-8 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                >
                    <x-heroicon-o-document class="w-5 h-5" />
                </button>

                <button
                    type="button"
                    wire:click="changeWinWidth"
                    class="inline-flex items-center justify-center rounded-lg border h-8 w-8 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                >
                    @if ($winWidth != "width:100%;")
                        <x-heroicon-o-arrows-pointing-out class="w-5 h-5" />
                    @else
                        <x-heroicon-o-arrows-pointing-in class="w-5 h-5" />
                    @endif
                </button>

                <button
                    type="button"
                    wire:click="changeWinPosition"
                    class="inline-flex items-center justify-center rounded-lg border h-8 w-8 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                >
                    <x-heroicon-o-arrows-right-left class="w-5 h-5" />
                </button>

                <button
                    type="button"
                    wire:click="$toggle('panelHidden')"
                    class="inline-flex items-center justify-center rounded-lg border h-8 w-8 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none"
                >
                    <x-heroicon-o-minus class="w-5 h-5" />
                </button>
            </div>
        </div>

        <!-- Messages -->
        <div id="messages" class="flex flex-col space-y-4 p-3 overflow-y-auto">
            @foreach ($messages as $message)
                <div class="chat-message">
                    @if ($message['role'] == "assistant")
                        <div class="flex items-end">
                            <div class="relative h-8 w-8 p-1 rounded-full text-white flex items-center justify-center bg-primary-600">
                                <x-heroicon-o-computer-desktop class="w-5 h-5" />
                            </div>
                            <div class="flex flex-col space-y-2 text-xs mx-2 order-2 items-start">
                                <div class="px-4 py-2 rounded-lg bg-gray-200 text-gray-600 max-w-xs lg:max-w-md">
                                    {!! \Illuminate\Mail\Markdown::parse($message['content']) !!}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-end justify-end">
                            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                <div class="px-4 py-2 rounded-lg bg-primary-600 text-white">
                                    {!! \Illuminate\Mail\Markdown::parse($message['content']) !!}
                                </div>
                            </div>
                            <div class="relative h-8 w-8 p-1 rounded-full bg-gray-300 text-white flex items-center justify-center">
                                <x-heroicon-o-user class="w-5 h-5 text-gray-600" />
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Input Area -->
        <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
            <div class="relative flex">
                <textarea
                    wire:model="question"
                    wire:keydown.ctrl.enter="sendMessage"
                    placeholder="Type your message..."
                    rows="1"
                    class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 pl-4 pr-10 bg-gray-100 rounded-md py-3 resize-none"
                ></textarea>
                <button
                    wire:click="sendMessage"
                    class="absolute right-2 items-center inset-y-0 flex"
                >
                    <x-heroicon-o-paper-airplane class="h-6 w-6 text-gray-600 hover:text-gray-900 transform rotate-90" />
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const messagesContainer = document.getElementById('messages');
            const textarea = document.querySelector('textarea');

            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
            }

            @this.on('sendmessage', () => {
                if (messagesContainer) {
                    setTimeout(() => {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }, 100);
                }
            });
        });
    </script>
</div>
