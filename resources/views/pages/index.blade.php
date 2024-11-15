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
            class="absolute inset-0 [background:radixaal-gradient(circle_500px_at_50%_200px,rgba(255,45,48,0.1),transparent)]"></div>

        <div class="flex items-center w-full max-w-6xl px-8 pt-8 pb-16 mx-auto">
            <div class="container relative max-w-4xl mx-auto mt-12 text-center space-y-16"
                 x-data="{
                    commandHistory: [],
                    currentCommand: '',
                    currentPath: '~/portfolio',
                    currentSection: 'intro',
                    sections: ['intro', 'skills', 'projects', 'contact'],
                    terminalOutput: [],
                    availableCommands: [
                        'help',
                        'ls',
                        'cd',
                        'clear',
                        'php artisan about',
                        'php artisan show:skills',
                        'php artisan show:projects',
                        'php artisan make:contact',
                        'composer require'
                    ],
                    async executeCommand(cmd) {
                        this.commandHistory.push(cmd);
                        this.terminalOutput.push({ type: 'command', content: cmd });

                        switch(cmd) {
                            case 'clear':
                                this.terminalOutput = [];
                                break;
                            case 'help':
                                this.terminalOutput.push({
                                    type: 'output',
                                    content: `Available commands:
                                    ${this.availableCommands.join('\n')}`
                                });
                                break;
                            case 'php artisan about':
                                this.currentSection = 'intro';
                                this.terminalOutput.push({
                                    type: 'output',
                                    content: 'Loading developer profile...'
                                });
                                break;
                            case 'php artisan show:skills':
                                this.currentSection = 'skills';
                                break;
                            case 'php artisan show:projects':
                                this.currentSection = 'projects';
                                break;
                            case 'php artisan make:contact':
                                this.currentSection = 'contact';
                                break;
                            default:
                                if(cmd.startsWith('cd ')) {
                                    this.currentPath = '~/' + cmd.slice(3);
                                } else {
                                    this.terminalOutput.push({
                                        type: 'error',
                                        content: `Command not found: ${cmd}`
                                    });
                                }
                        }
                        this.currentCommand = '';
                    }
                 }">

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
                    <div class="mt-4 text-left font-mono h-96 overflow-y-auto">
                        <!-- Command History -->
                        <template x-for="output in terminalOutput" :key="output.content">
                            <div :class="{
                                'text-red-400': output.type === 'error',
                                'text-green-400': output.type === 'output',
                                'text-blue-400': output.type === 'command'
                            }">
                                <template x-if="output.type === 'command'">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-red-400">➜</span>
                                        <span class="text-blue-400" x-text="currentPath"></span>
                                        <span x-text="output.content"></span>
                                    </div>
                                </template>
                                <template x-if="output.type !== 'command'">
                                    <div x-text="output.content"></div>
                                </template>
                            </div>
                        </template>

                        <!-- Current Command Input -->
                        <div class="flex items-center space-x-2 mt-2">
                            <span class="text-red-400">➜</span>
                            <span class="text-blue-400" x-text="currentPath"></span>
                            <input
                                type="text"
                                x-model="currentCommand"
                                @keydown.enter="executeCommand(currentCommand)"
                                class="flex-1 bg-transparent border-none outline-none text-white font-mono"
                                placeholder="Type 'help' for available commands"
                            >
                        </div>
                    </div>
                </div>

                <!-- Dynamic Content Section -->
                <div x-show="currentSection === 'intro'" x-transition>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-red-200 dark:border-red-900">
                        <x-ui.avatar class="w-24 h-24 mx-auto border-2 border-red-500 rounded-lg shadow-xl"/>
                        <h2 class="text-2xl font-bold text-red-500 mt-4">Senior Laravel Developer</h2>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">
                            Crafting elegant solutions with Laravel and modern web technologies
                        </p>
                    </div>
                </div>

                <!-- Skills Section -->
                <div x-show="currentSection === 'skills'" x-transition>
                    <!-- Your existing skills grid here -->
                </div>

                <!-- Projects Section -->
                <div x-show="currentSection === 'projects'" x-transition>
                    <x-project-showcase :projects="$projects"></x-project-showcase>
                    <!-- Your existing projects grid here -->
                </div>

                <!-- Contact Section -->
                <div x-show="currentSection === 'contact'" x-transition>
                    <x-ui.contact-form
                        class="bg-white dark:bg-gray-800 rounded-lg p-8 border border-red-200 dark:border-red-900"/>
                </div>

                <!-- Command Helper -->
                <div
                    class="fixed bottom-4 left-4 right-4 bg-gray-900 text-white p-4 rounded-lg border border-red-500/20"
                    x-show="currentCommand.length > 0">
                    <div class="font-mono text-sm">
                        <div>Suggestions:</div>
                        <template x-for="cmd in availableCommands.filter(c => c.startsWith(currentCommand))" :key="cmd">
                            <div class="text-gray-400" x-text="cmd"></div>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>
