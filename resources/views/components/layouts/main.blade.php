<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('dark_mode') === 'true' }"
      x-init="$watch('darkMode', val => {
          localStorage.setItem('dark_mode', val);
          if (val) {
              document.documentElement.classList.add('dark');
          } else {
              document.documentElement.classList.remove('dark');
          }
      })"
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Used to add dark mode right away, adding here prevents any flicker -->
        <script>
            if (typeof(Storage) !== "undefined") {
                if(localStorage.getItem('dark_mode') === 'true'){
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://kit.fontawesome.com/d1901f5db9.js" crossorigin="anonymous"></script>

        <title>{{ $title ?? 'Jordan Partridge' }}</title>

        <!-- Meta description for SEO -->
        <meta name="description" content="Personal website of Jordan Partridge - Engineering Manager, Laravel Developer, Army Veteran, and Cycling Enthusiast">
    </head>
    <body class="min-h-screen antialiased bg-gradient-to-tr from-white to-gray-50 dark:bg-gradient-to-br dark:from-gray-950 dark:to-gray-900 transition-colors duration-300">
        {{ $slot }}
        <livewire:toast />

        <!-- Footer -->
        <footer class="py-6 mt-16 text-center text-gray-600 dark:text-gray-400 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="border-t border-gray-200 dark:border-gray-800 pt-6 transition-colors duration-300">
                    <p>&copy; {{ date('Y') }} Jordan Partridge. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
