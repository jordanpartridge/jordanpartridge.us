
.<header x-data="{ mobileMenuOpen: false }" class="w-full bg-white dark:bg-gray-900 shadow-sm">
    <div class="relative z-20 flex items-center justify-between w-full h-20 max-w-6xl px-6 mx-auto">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center space-x-4 md:space-x-8">
                <a href="{{ route('home') }}" class="flex items-center shrink-0 transition-transform duration-200 hover:scale-105">
                    <x-ui.logo class="block w-auto h-8 text-gray-800 fill-current dark:text-gray-200"/>
                </a>

                <nav class="hidden md:flex space-x-1 md:space-x-4">
                    <x-ui.nav-link href="/" dusk="nav-home">Home</x-ui.nav-link>
                    <x-ui.nav-link href="/bike">Bike Joy</x-ui.nav-link>
                    <x-ui.nav-link href="/software-development" dusk="nav-software-development">Software Development</x-ui.nav-link>
                    <x-ui.nav-link href="/services" dusk="nav-services">Services</x-ui.nav-link>
                    <x-ui.nav-link href="/contact" dusk="nav-contact">Contact</x-ui.nav-link>
                    @if (view()->exists('pages.blog.index'))
                        <x-ui.nav-link href="/blog">Blog</x-ui.nav-link>
                    @endif
                </nav>
            </div>

            <div class="flex items-center space-x-4">
                <a href="/work-with-me" dusk="nav-work-with-me" class="hidden md:flex items-center px-4 py-2 font-medium text-white transition-all duration-200 rounded-full bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 shadow-md hover:shadow-lg">
                    Work With Me
                </a>
                <div class="w-10 h-10 overflow-hidden rounded-full shadow-sm">
                    <x-ui.light-dark-switch></x-ui.light-dark-switch>
                </div>
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="relative flex items-center justify-center w-10 h-10 md:hidden focus:outline-none"
                        aria-label="Toggle mobile menu">
                    <div class="flex flex-col items-center justify-center w-6 h-6">
                        <span :class="{ '-rotate-45 translate-y-1.5' : mobileMenuOpen }"
                              class="block w-full h-0.5 bg-gray-600 dark:bg-gray-300 transition duration-300 ease-out rounded-full"></span>
                        <span :class="{ 'opacity-0' : mobileMenuOpen }"
                              class="block w-full h-0.5 my-1 bg-gray-600 dark:bg-gray-300 transition duration-300 ease-out rounded-full"></span>
                        <span :class="{ 'rotate-45 -translate-y-1.5' : mobileMenuOpen }"
                              class="block w-full h-0.5 bg-gray-600 dark:bg-gray-300 transition duration-300 ease-out rounded-full"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="fixed inset-0 z-40 pt-20 bg-white dark:bg-gray-900 md:hidden"
         @click.away="mobileMenuOpen = false"
         x-cloak>
        <nav class="flex flex-col items-center space-y-4 text-lg">
            <x-ui.nav-link href="/" dusk="nav-home">Home</x-ui.nav-link>
            <x-ui.nav-link href="/bike">Bike Joy</x-ui.nav-link>
            <x-ui.nav-link href="/software-development" dusk="nav-software-development">Software Development</x-ui.nav-link>
            <x-ui.nav-link href="/services" dusk="nav-services">Services</x-ui.nav-link>
            <x-ui.nav-link href="/contact" dusk="nav-contact">Contact</x-ui.nav-link>
            @if (view()->exists('pages.blog.index'))
                <x-ui.nav-link href="/blog">Blog</x-ui.nav-link>
            @endif
            <x-ui.nav-link href="/work-with-me" dusk="nav-work-with-me" class="mt-4 px-4 py-2 font-medium text-white transition-all duration-200 rounded-full bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 shadow-md hover:shadow-lg">
                Work With Me
            </x-ui.nav-link>
        </nav>
    </div>
</header>
