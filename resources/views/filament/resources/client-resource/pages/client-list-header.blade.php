@props([
    'actions' => null,
    'heading',
    'subheading' => null,
])

<header {{ $attributes->class(['fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between p-4 sm:py-8']) }}>
    <div>
        <h1 class="fi-header-heading text-2xl font-bold tracking-tight sm:text-3xl">
            {{ $heading }}
        </h1>

        @if ($subheading)
            <p class="fi-header-subheading mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $subheading }}
            </p>
        @endif
    </div>

    <div class="flex items-center gap-4 ms-auto">
        @if ($actions)
            <div class="flex items-center gap-4 ms-auto">
                {{ $actions }}
            </div>
        @endif
    </div>
</header>

<div class="px-4 pb-4 -mt-4">
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('filament.admin.resources.clients.index') }}"
           class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-8 px-3 text-sm bg-white text-gray-800 border-gray-300 hover:bg-gray-50 focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600 dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-600 dark:hover:border-gray-500 dark:text-gray-200 dark:focus:text-primary-400 dark:focus:border-primary-400 dark:focus:bg-gray-800">
            All Clients
        </a>

        <a href="{{ route('filament.admin.resources.clients.index', ['tableFilters' => ['status' => ['value' => 'lead']]]) }}"
           class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-8 px-3 text-sm bg-warning-500 border-warning-600 hover:bg-warning-600 hover:border-warning-700 focus:ring-warning-700 focus:text-warning-700 focus:bg-warning-50 focus:border-warning-700 dark:bg-warning-600 dark:hover:bg-warning-700 dark:border-warning-700 dark:hover:border-warning-800 dark:text-white dark:focus:text-warning-700 dark:focus:border-warning-700 dark:focus:bg-warning-700/10 text-white">
            <x-filament::icon
                alias="heroicon-m-funnel"
                icon="heroicon-m-funnel"
                class="w-4 h-4"
            />
            Leads
        </a>

        <a href="{{ route('filament.admin.resources.clients.index', ['tableFilters' => ['status' => ['value' => 'active']]]) }}"
           class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-8 px-3 text-sm bg-success-500 border-success-600 hover:bg-success-600 hover:border-success-700 focus:ring-success-700 focus:text-success-700 focus:bg-success-50 focus:border-success-700 dark:bg-success-600 dark:hover:bg-success-700 dark:border-success-700 dark:hover:border-success-800 dark:text-white dark:focus:text-success-700 dark:focus:border-success-700 dark:focus:bg-success-700/10 text-white">
            <x-filament::icon
                alias="heroicon-m-check-badge"
                icon="heroicon-m-check-badge"
                class="w-4 h-4"
            />
            Active Clients
        </a>

        <a href="{{ route('filament.admin.resources.clients.index', ['tableFilters' => ['status' => ['value' => 'former']]]) }}"
           class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-8 px-3 text-sm bg-gray-500 border-gray-600 hover:bg-gray-600 hover:border-gray-700 focus:ring-gray-700 focus:text-gray-700 focus:bg-gray-50 focus:border-gray-700 dark:bg-gray-600 dark:hover:bg-gray-700 dark:border-gray-700 dark:hover:border-gray-800 dark:text-white dark:focus:text-gray-700 dark:focus:border-gray-700 dark:focus:bg-gray-700/10 text-white">
            <x-filament::icon
                alias="heroicon-m-archive-box"
                icon="heroicon-m-archive-box"
                class="w-4 h-4"
            />
            Former Clients
        </a>

        <a href="{{ route('filament.admin.resources.clients.index', ['tableFilters' => ['user_id' => ['value' => auth()->id()]]]) }}"
           class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-8 px-3 text-sm bg-primary-500 border-primary-600 hover:bg-primary-600 hover:border-primary-700 focus:ring-primary-700 focus:text-primary-700 focus:bg-primary-50 focus:border-primary-700 dark:bg-primary-600 dark:hover:bg-primary-700 dark:border-primary-700 dark:hover:border-primary-800 dark:text-white dark:focus:text-primary-700 dark:focus:border-primary-700 dark:focus:bg-primary-700/10 text-white">
            <x-filament::icon
                alias="heroicon-m-user"
                icon="heroicon-m-user"
                class="w-4 h-4"
            />
            My Clients
        </a>
    </div>
</div>