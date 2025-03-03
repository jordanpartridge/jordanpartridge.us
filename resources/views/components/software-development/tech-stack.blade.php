<div class="mb-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-tr from-primary-50/30 to-secondary-50/30 dark:from-primary-900/20 dark:to-secondary-900/20 rounded-3xl transform rotate-1 scale-105 -z-10"></div>
    <div class="relative z-10">
        <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500 dark:from-primary-400 dark:to-secondary-400 mb-2">
            Tech Stack
        </h2>
        <p class="text-center text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">The modern tools I use to build amazing web experiences</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach ([
            ['Laravel', 'https://laravel.com/img/logomark.min.svg', 'Expert-level proficiency in Laravel, capable of architecting complex applications with clean, maintainable code.', 'https://laravel.com'],
            ['Alpine.js + Livewire', 'https://alpinejs.dev/alpine_long.svg', 'Creating dynamic, reactive interfaces without the complexity of a full front-end framework.', 'https://livewire.laravel.com'],
            ['Tailwind CSS', 'https://tailwindcss.com/_next/static/media/tailwindcss-mark.3c5441fc7a190fb1800d4a5c7f07ba4b1345a9c8.svg', 'Rapid UI development with utility-first CSS that scales with your project.', 'https://tailwindcss.com'],
            ['Laravel Folio & Volt', 'https://laravel.com/img/logomark.min.svg', 'The latest Laravel tools for building modern, file-based routing and reactive components.', 'https://laravel.com/docs/folio'],
            ['API Integration', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwYXRoIGQ9Ik05IDE5YzAtMS4yNS0uMi0yLjQ1LS45My0zLjU3YTguNjkgOC42OSAwIDAgMC0yLjU2LTIuNTZDNC40NSAxMiAzLjI1IDExLjggMiAxMS44IiBzdHJva2U9InJnYig5OSAxMDIgMjQxKSI+PC9wYXRoPjxwYXRoIGQ9Ik0yMiAxOEMxOC41IDE4IDE1IDE0LjUgMTUgMTFhMiAyIDAgMSAxIDQgMCAxIDEgMCAwIDAgMiAwIDQgNCAwIDAgMC00LTRDMTMuOSA3IDExIDkuOSAxMSAxMy44YTE0Ljc2IDE0Ljc2IDAgMCAwIDEuODggN0M5LjMgMTkuMzYgNCAxNi45MiA0IDExYTggOCAwIDAgMSA4LThjNS4yMDUgMCA4IDMuNDIgOCA4IDAgNS42LTMuMjk0IDcgLTYuNSA3UzYgMTggNiAxOCIgc3Ryb2tlPSJyZ2IoMjQ0IDE1OCA5OSkiPjwvcGF0aD48L3N2Zz4=', 'Seamless integration with external services and data sources for powerful web applications.', '#'],
            ['TDD & CI/CD', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwYXRoIGQ9Ik03IDIyaC0yYTUgNSAwIDAgMS01LTV2LTE0YTIgMiAwIDAgMSAyLTJoMTRhMiAyIDAgMCAxIDIgMnYxNCIgc3Ryb2tlPSJyZ2IoNzQgMjIyIDE0MCkiPjwvcGF0aD48cGF0aCBkPSJNNiAyYTIgMiAwIDAgMC0zIDJNMTggMmEyIDIgMCAwIDEgMiAyTTE3IDE2bDQgNE0xNCAyMmg4IiBzdHJva2U9InJnYig3NCAyMjIgMTQwKSI+PC9wYXRoPjxwYXRoIGQ9Ik01IDEzYTEgMSAwIDAgMC0xIDFhMSAxIDAgMCAwIDAgMiAxIDEgMCAwIDAgMS0xTTQgOGgzbTYtNGwuNS0yLTIgLjVhOC41IDguNSAwIDEgMCA4LjUgOC41bC41LTItMiAuNSIgc3Ryb2tlPSJyZ2IoMTYwIDEyMCAyNDkpIj48L3BhdGg+PC9zdmc+', 'Building with automated testing and continuous deployment for reliability and quality.', '#'],
        ] as [$tech, $logoUrl, $description, $link])
            <div
                class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-6 shadow-lg backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:shadow-primary-500/50 dark:hover:shadow-secondary-500/50 transition duration-300 transform hover:-translate-y-1">
                <div class="flex items-center mb-4">
                    <img src="{{ $logoUrl }}" alt="{{ $tech }}" class="w-10 h-10 mr-4 object-contain">
                    <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $tech }}</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $description }}</p>
                @if ($link !== '#')
                <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-sm font-medium">
                    Learn more
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                @endif
            </div>
        @endforeach
    </div>
</div>
