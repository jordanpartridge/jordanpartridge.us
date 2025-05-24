<?php

use function Livewire\Volt\state;

state([
    'projects' => [
        [
            'name'         => 'MyCareerAdvisor',
            'description'  => 'Full-featured e-commerce platform with real-time inventory, payments, and analytics. Built with Laravel and modern web technologies.',
            'url'          => 'https://www.mycareeradvisor.com',
            'github'       => 'https://github.com/username/shopmaster-pro',
            'headerClass'  => 'bg-gradient-to-r from-red-500 to-pink-600',
            'preview'      => 'No cost career services platform for job seekers seeks to remove the barriers to entry for job seekers.',
            'technologies' => [
                ['name' => 'Laravel', 'color' => 'red'],
                ['name' => 'Vue', 'color' => 'blue'],
                ['name' => 'API', 'color' => 'green'],
                ['name' => 'Webhooks', 'color' => 'red']
            ]
        ],
        [
            'name'         => 'Jordan Partridge.us',
            'description'  => 'Modern analytics dashboard providing real-time insights and custom reporting for SaaS businesses.',
            'url'          => 'https://www.jordanpartridge.us',
            'github'       => 'https://github.com/username/saas-metrics',
            'headerClass'  => 'bg-gradient-to-r from-blue-500 to-indigo-600',
            'preview'      => 'Track MRR, ARR, customer behavior, and engagement metrics in real-time with customizable reporting.',
            'technologies' => [
                ['name' => 'Laravel', 'color' => 'red'],
                ['name' => 'Vue.js', 'color' => 'green'],
                ['name' => 'PostgreSQL', 'color' => 'blue'],
                ['name' => 'Redis', 'color' => 'purple']
            ]
        ],
        [
            'name'         => 'TaskFlow',
            'description'  => 'Collaborative task management platform featuring real-time updates and automated workflows.',
            'url'          => 'https://tasks.example.com',
            'github'       => 'https://github.com/username/taskflow',
            'headerClass'  => 'bg-gradient-to-r from-green-500 to-teal-600',
            'preview'      => 'Streamline team collaboration with real-time notifications, automated task assignment, and performance tracking.',
            'technologies' => [
                ['name' => 'Laravel', 'color' => 'red'],
                ['name' => 'Tailwind', 'color' => 'blue'],
                ['name' => 'Alpine.js', 'color' => 'indigo'],
                ['name' => 'SQLite', 'color' => 'gray']
            ]
        ]
    ]
]);
?>
<x-layouts.marketing>
    @volt('home')
    <div
        class="relative flex flex-col items-center justify-center w-full min-h-screen overflow-hidden bg-gradient-to-br from-red-50 to-pink-50 dark:from-gray-900 dark:to-red-950 transition-colors duration-300"
        x-cloak>
        <div
            class="absolute inset-0 [background:radial-gradient(circle_500px_at_50%_200px,rgba(255,45,48,0.1),transparent)]">
        </div>

        <div class="flex items-center w-full max-w-6xl px-8 pt-8 pb-16 mx-auto">
            <div class="container relative max-w-4xl mx-auto mt-12 text-center space-y-16"
                 x-data="{
                    commandHistory: [],
                    currentCommand: '',
                    currentPath: '~/jordanpartridge.us',
                    currentSection: 'intro',
                    sections: ['intro', 'skills', 'projects', 'contact'],
                    terminalOutput: [],
                    cursorPosition: 0,
                    historyIndex: -1,
                    showSuggestions: false,
                    availableCommands: [
                        'help',
                        'ls',
                        'cd',
                        'cls',
                        'clear',
                        'php artisan about',
                        'php artisan show:skills',
                        'php artisan show:projects',
                        'php artisan make:contact',
                        'php artisan quote:website',
                        'php artisan consult:schedule',
                        'php artisan make:coffee',
                        'composer require jordanpartridge/expertise',
                        'composer require jordanpartridge/strava-client'
                    ],
                    get filteredSuggestions() {
                        return this.currentCommand.length > 0
                            ? this.availableCommands.filter(cmd => cmd.toLowerCase().startsWith(this.currentCommand.toLowerCase()))
                            : [];
                    },
                    selectedSuggestionIndex: 0,
                    async executeCommand(cmd) {
                        this.commandHistory.push(cmd);
                        this.historyIndex = -1;
                        this.terminalOutput.push({ type: 'command', content: cmd });
                        this.showSuggestions = false;

                        // Special handling for clear commands
                        if (cmd === 'clear' || cmd === 'cls') {
                            this.terminalOutput = [];
                            this.currentSection = 'intro';
                            this.currentCommand = '';
                            return;
                        }

                        // Show loading indicator
                        const loadingIndex = this.terminalOutput.length;
                        this.terminalOutput.push({
                            type: 'loading',
                            content: 'Executing command...'
                        });

                        try {
                            // Make API call to execute real artisan command
                            const response = await fetch('/api/terminal/execute', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                },
                                body: JSON.stringify({ command: cmd })
                            });

                            const result = await response.json();
                            
                            // Remove loading indicator
                            this.terminalOutput.splice(loadingIndex, 1);

                            // Add command result
                            this.terminalOutput.push({
                                type: result.type,
                                content: result.content
                            });

                            // Update current section based on command
                            this.updateCurrentSection(cmd);

                        } catch (error) {
                            // Remove loading indicator
                            this.terminalOutput.splice(loadingIndex, 1);
                            
                            this.terminalOutput.push({
                                type: 'error',
                                content: 'Network error: Could not execute command'
                            });
                        }

                        this.currentCommand = '';
                        
                        // Scroll to bottom
                        this.$nextTick(() => {
                            const terminal = document.getElementById('terminal-content');
                            if (terminal) {
                                terminal.scrollTop = terminal.scrollHeight;
                            }
                        });
                    },
                    updateCurrentSection(cmd) {
                        // Update current section for navigation commands
                        if (cmd === 'php artisan about') {
                            this.currentSection = 'intro';
                        } else if (cmd === 'php artisan show:skills') {
                            this.currentSection = 'skills';
                        } else if (cmd === 'php artisan show:projects') {
                            this.currentSection = 'projects';
                        } else if (cmd === 'php artisan make:contact') {
                            this.currentSection = 'contact';
                        }
                    }
                }"
                 @keydown.up.prevent="
                    if (showSuggestions && filteredSuggestions.length > 0) {
                        selectedSuggestionIndex = (selectedSuggestionIndex - 1 + filteredSuggestions.length) % filteredSuggestions.length;
                    } else if (commandHistory.length > 0) {
                        historyIndex = Math.min(historyIndex + 1, commandHistory.length - 1);
                        currentCommand = commandHistory[commandHistory.length - 1 - historyIndex];
                    }
                "
                 @keydown.down.prevent="
                    if (showSuggestions && filteredSuggestions.length > 0) {
                        selectedSuggestionIndex = (selectedSuggestionIndex + 1) % filteredSuggestions.length;
                    } else if (historyIndex > 0) {
                        historyIndex--;
                        currentCommand = commandHistory[commandHistory.length - 1 - historyIndex];
                    } else if (historyIndex === 0) {
                        historyIndex = -1;
                        currentCommand = '';
                    }
                "
                 @keydown.tab.prevent="
                    if (filteredSuggestions.length > 0) {
                        currentCommand = filteredSuggestions[selectedSuggestionIndex];
                        showSuggestions = false;
                    }
                "
                 @keydown.enter.prevent="
                    if (filteredSuggestions.length > 0) {
                        currentCommand = filteredSuggestions[selectedSuggestionIndex];
                        showSuggestions = false;
                    }
                    "
                 @keydown.esc="showSuggestions = false">

                <!-- Interactive Terminal -->
                <div class="relative p-8 bg-gray-900 rounded-lg border border-red-500/20 shadow-2xl overflow-hidden">
                    <!-- Terminal Header -->
                    <div class="absolute top-0 left-0 right-0 h-6 bg-gray-800 flex items-center px-4">
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                    </div>

                    <!-- Terminal Content -->
                    <div class="mt-4 text-left font-mono h-96 overflow-y-auto" id="terminal-content">
                        <!-- Command History -->
                        <template x-for="output in terminalOutput" :key="output.content">
                            <div :class="{
                                'text-red-400': output.type === 'error',
                                'text-green-400': output.type === 'output',
                                'text-blue-400': output.type === 'command',
                                'text-yellow-400': output.type === 'loading'
                            }">
                                <template x-if="output.type === 'command'">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-red-400">➜</span>
                                        <span class="text-blue-400" x-text="currentPath"></span>
                                        <span x-text="output.content"></span>
                                    </div>
                                </template>
                                <template x-if="output.type !== 'command'">
                                    <div>
                                        <template x-if="output.type === 'loading'">
                                            <span>⏳ <span x-text="output.content"></span></span>
                                        </template>
                                        <template x-if="output.type !== 'loading'">
                                            <pre x-text="output.content" class="whitespace-pre-wrap font-mono text-sm"></pre>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Current Command Input -->
                        <div class="relative">
                            <div class="flex items-center space-x-2">
                                <span class="text-red-400">➜</span>
                                <span class="text-blue-400" x-text="currentPath"></span>
                                <input
                                    type="text"
                                    x-model="currentCommand"
                                    @input="showSuggestions = currentCommand.length > 0"
                                    @keydown.enter="executeCommand(currentCommand)"
                                    @click="showSuggestions = currentCommand.length > 0"
                                    class="flex-1 bg-transparent border-none outline-none text-white font-mono"
                                    placeholder="Type 'help' for available commands"
                                >
                            </div>

                            <!-- In-Terminal Suggestions -->
                            <div
                                x-show="showSuggestions && filteredSuggestions.length > 0"
                                class="absolute left-0 right-0 mt-1 bg-gray-800 border border-gray-700 rounded-md overflow-hidden z-10">
                                <template x-for="(suggestion, index) in filteredSuggestions" :key="suggestion">
                                    <div
                                        @click="currentCommand = suggestion; executeCommand(suggestion)"
                                        @mouseover="selectedSuggestionIndex = index"
                                        :class="{
                                            'px-4 py-2 cursor-pointer hover:bg-gray-700': true,
                                            'bg-gray-700': selectedSuggestionIndex === index
                                        }"
                                        class="text-gray-300">
                                        <span x-text="suggestion"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Content Sections remain the same -->
                <div x-show="currentSection === 'intro'" x-transition>
                    <h1>Coming soon</h1>
                </div>

                <div x-show="currentSection === 'skills'" x-transition>
                    <!-- ... -->
                </div>

                <div x-show="currentSection === 'projects'" x-transition>
                    <x-project-showcase :projects="$projects"></x-project-showcase>
                </div>

                <div x-show="currentSection === 'contact'" x-transition>
                    <!-- ... -->
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>
