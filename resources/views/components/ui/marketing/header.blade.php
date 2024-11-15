<header x-data="{ mobileMenuOpen: false }" class="w-full bg-base-100 shadow-sm">
    <div class="relative z-20 flex items-center justify-between w-full h-20 max-w-6xl px-6 mx-auto">
        <!-- Logo and Navigation Container -->
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center space-x-4 md:space-x-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center shrink-0 transition-transform duration-200 hover:scale-105">
                    <x-ui.logo class="block w-auto h-8 text-base-content fill-current" />
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:block bg-gray-900/50 backdrop-blur-lg border border-gray-800 shadow-2xl rounded-2xl">
                    <x-menu class="menu menu-horizontal px-6 py-4 flex items-center justify-center space-x-8" activate-by-route>
                        <x-menu-item
                            href="/"
                            activate-by-route="home"
                            class="flex items-center justify-center gap-3 px-4 py-2 rounded-xl text-gray-400 hover:text-white group transition-all duration-300"
                        >
                            <x-icon name="o-home" class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-300" />
                            <span class="font-medium tracking-wide">Home</span>
                        </x-menu-item>

                        <x-menu-item
                            href="/bike"
                            :active="request()->routeIs('bike')"
                            class="flex items-center justify-center gap-3 px-4 py-2 rounded-xl text-gray-400 hover:text-white group transition-all duration-300"
                        >
                            <x-icon name="o-bolt" class="w-5 h-5 shrink-0 group-hover:scale-110 group-hover:text-yellow-400 transition-all duration-300" />
                            <span class="font-medium tracking-wide">Bike Joy</span>
                        </x-menu-item>

                        <x-menu-item
                            href="/software-development"
                            class="flex items-center justify-center gap-3 px-4 py-2 rounded-xl text-gray-400 hover:text-white group transition-all duration-300"
                        >
                            <x-icon name="o-command-line" class="w-5 h-5 shrink-0 group-hover:scale-110 group-hover:text-emerald-400 transition-all duration-300" />
                            <span class="font-medium tracking-wide">Software Development</span>
                        </x-menu-item>

                        <x-menu-item
                            href="/blog"
                            :active="request()->routeIs('blog')"
                            class="flex items-center justify-center gap-3 px-4 py-2 rounded-xl text-gray-400 hover:text-white group transition-all duration-300"
                        >
                            <x-icon name="o-newspaper" class="w-5 h-5 shrink-0 group-hover:scale-110 group-hover:text-blue-400 transition-all duration-300" />
                            <span class="font-medium tracking-wide">Blog</span>
                        </x-menu-item>
                    </x-menu>
                </div>            </div>

            <!-- Mobile Menu Button -->
            <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="btn btn-ghost md:hidden"
                aria-label="Toggle mobile menu"
            >
                <div class="flex flex-col items-center justify-center w-6 h-6">
                    <span
                        :class="{ '-rotate-45 translate-y-1.5' : mobileMenuOpen }"
                        class="block w-full h-0.5 bg-base-content transition duration-300 ease-out rounded-full"
                    ></span>
                    <span
                        :class="{ 'opacity-0' : mobileMenuOpen }"
                        class="block w-full h-0.5 my-1 bg-base-content transition duration-300 ease-out rounded-full"
                    ></span>
                    <span
                        :class="{ 'rotate-45 -translate-y-1.5' : mobileMenuOpen }"
                        class="block w-full h-0.5 bg-base-content transition duration-300 ease-out rounded-full"
                    ></span>
                </div>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="fixed inset-0 z-40 pt-20 bg-base-100 md:hidden"
        @click.away="mobileMenuOpen = false"
        x-cloak
    >
        <nav class="menu menu-vertical px-4">
            <x-menu-item href="/" :active="request()->routeIs('home')" class="menu-title">
                Home
            </x-menu-item>
            <x-menu-item href="/bike" icon="o-bolt" :active="request()->routeIs('bike')" class="menu-title">
                Bike Joy
            </x-menu-item>
            <x-menu-item href="/software-development" icon="o-arrow-uturn-right" :active="request()->routeIs('software-development')" class="menu-title">
                Software Development
            </x-menu-item>
            <x-menu-item href="/blog" :active="request()->routeIs('blog')" class="menu-title">
                Blog
            </x-menu-item>
        </nav>
    </div>
</header>
