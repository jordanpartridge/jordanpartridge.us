@props([
    'breadcrumbs' => [],
    'navigation',
    'navigationGroup',
    'navigationItems',
    'sidebarCollapsible' => true,
    'sidebarWidth' => null,
])

<div
    x-data="{
        navigationItemLists: $refs.navigations.getNavigationItems(),
        sidebarCollapsible: @js($sidebarCollapsible)
    }"
    class="flex w-full min-h-screen overflow-x-clip"
>
    {{-- Main Content --}}
    <div class="flex flex-col flex-1 w-full min-h-screen bg-white filament-main dark:bg-gray-900">
        {{ $slot }}
    </div>

    {{-- Modals for Social Preview --}}
    @livewire('social-preview-modal', ['title' => '', 'body' => '', 'excerpt' => ''], key('social-preview-modal'))
</div>
