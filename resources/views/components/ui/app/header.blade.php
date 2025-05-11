<header x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 dark:bg-gray-900/90 border-b border-gray-200/70 dark:border-gray-700/30 backdrop-blur-md transition-all duration-300 shadow-sm">

<!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center shrink-0 group">
                <x-ui.logo class="block w-auto text-gray-800 fill-current h-8 dark:text-gray-200 transition-transform duration-300 transform group-hover:scale-110" />
            </a>

            <!-- Navigation -->
            <div :class="{ 'absolute left-0' : open, 'relative' : !open }" class="flex flex-col justify-start w-full sm:relative sm:flex-row sm:justify-between" x-cloak>
                @php
                    $navLinks = ['Dashboard' => '/dashboard-x', 'Learn More' => '/learn', 'Architecture' => route('architecture')];
                @endphp
                <!-- Navigation Links -->
                <nav :class="{'flex flex-col bg-white/95 dark:bg-gray-900/95 backdrop-blur-md relative z-50 w-full h-auto px-4 py-5 left-0 mt-16 border-t border-gray-200/70 dark:border-gray-700/30': open, 'hidden': ! open}"
                     class="items-center space-y-3 sm:space-x-4 sm:space-y-0 sm:mt-0 sm:bg-transparent sm:p-0 sm:relative sm:flex sm:-my-px sm:ml-8"
                     x-cloak>
                    @foreach ($navLinks as $title => $route)
                        <x-ui.nav-link href="{{ $route }}" class="relative overflow-hidden group">
                            <span class="relative z-10">{{ $title }}</span>
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-secondary-500 group-hover:w-full transition-all duration-300"></span>
                        </x-ui.nav-link>
                    @endforeach
                </nav>

                <div class="flex items-center">
                    <div class="flex items-center ml-4">
                        <div class="w-10 h-10 overflow-hidden rounded-full shadow-sm">
                            <x-ui.light-dark-switch></x-ui.light-dark-switch>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div x-data="{ dropdownOpen: false }"
                        :class="{ 'block z-50 w-full p-4 border-t border-gray-100 bg-white dark:bg-gray-900 dark:border-gray-800' : open, 'hidden': ! open }"
                        class="relative flex-shrink-0 sm:p-0 sm:flex sm:w-auto sm:bg-transparent sm:items-center sm:ml-2"
                        x-cloak
                    >
                        <button @click="dropdownOpen=!dropdownOpen"
                                class="inline-flex items-center justify-between w-full sm:px-3.5 sm:py-2 py-2.5 px-4 text-sm font-medium text-gray-700 transition-all duration-200 bg-white border border-gray-200 rounded-full shadow-sm dark:text-white/90 dark:border-gray-700 dark:bg-gray-800 hover:shadow-md dark:hover:bg-gray-700/70 hover:bg-gray-50 hover:text-primary-600 dark:hover:text-primary-300 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </button>

                        <div x-show="dropdownOpen"
                             @click.away="dropdownOpen=false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 sm:scale-95"
                             x-transition:enter-end="transform opacity-100 sm:scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="transform opacity-100 sm:scale-100"
                             x-transition:leave-end="transform opacity-0 sm:scale-95"
                             class="absolute top-0 right-0 z-50 w-full mt-16 sm:mt-12 sm:origin-top-right sm:w-48"
                             x-cloak>
                            <div class="p-4 pt-0 mt-1 space-y-3 bg-white rounded-xl shadow-lg dark:text-white/90 dark:bg-gray-800 sm:p-3 sm:space-y-1 sm:border border-gray-200/70 dark:border-gray-700/50">
                                <a href="{{ route('profile.edit') }}"
                                   class="relative flex cursor-pointer select-none items-center rounded-lg py-2 px-4 sm:px-3 sm:py-2 text-sm outline-none transition-colors hover:bg-gradient-to-r hover:from-primary-50 hover:to-secondary-50 dark:hover:from-primary-900/20 dark:hover:to-secondary-900/20 hover:text-primary-600 dark:hover:text-primary-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2 text-primary-500"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <span>Edit Profile</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="relative w-full flex cursor-pointer select-none items-center rounded-lg py-2 px-4 sm:px-3 sm:py-2 text-sm outline-none transition-colors hover:bg-gradient-to-r hover:from-red-50 hover:to-orange-50 dark:hover:from-red-900/20 dark:hover:to-orange-900/20 hover:text-red-600 dark:hover:text-red-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2 text-red-500"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" x2="9" y1="12" y2="12"></line></svg>
                                        <span>Log out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Switch and Hamburger -->
                    <div :class="{ 'right-4' : open, 'right-0' : !open }" class="absolute top-0 flex items-center mt-3 space-x-2 sm:right-0 sm:hidden">
                        <div class="block w-10 h-10 overflow-hidden rounded-full shadow-sm" x-cloak>
                            <x-ui.light-dark-switch></x-ui.light-dark-switch>
                        </div>
                        <button @click="open = !open"
                                class="inline-flex items-center justify-center p-2 text-gray-600 transition-all duration-200 rounded-full bg-gray-100/80 dark:bg-gray-800/80 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-200/80 dark:hover:bg-gray-700/80 focus:outline-none">
                            <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
