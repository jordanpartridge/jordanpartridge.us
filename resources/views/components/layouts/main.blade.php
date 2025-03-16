@props([
    'title' => config('app.name'),
    'metaDescription' => 'Software engineer, cycling enthusiast, and adventure seeker.',
    'metaImage' => asset('images/og-image.jpg'),
    'metaType' => 'website',
    'metaUrl' => null,
    'metaJsonLd' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Used to add dark mode right away, adding here prevents any flicker -->
        <script>
            // Define the dark mode theme based on localStorage, system preference, or default to light
            let darkModeEnabled = false;
            const userThemePreference = localStorage.getItem('theme');

            if (userThemePreference === 'dark') {
                darkModeEnabled = true;
            } else if (userThemePreference === null && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                darkModeEnabled = true;
            }

            // Apply theme class to html element
            if (darkModeEnabled) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        @if (isset($head))
            {{ $head }}
        @else
            <title>{{ $title }}</title>
            <meta name="description" content="{{ $metaDescription }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles and Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://kit.fontawesome.com/d1901f5db9.js" crossorigin="anonymous"></script>

        @stack('scripts')
    </head>
    <body class="min-h-screen antialiased bg-gradient-to-tr from-white to-gray-50 dark:bg-gradient-to-br dark:from-gray-950 dark:to-gray-900 transition-colors duration-300">
        {{ $slot }}
    </body>
</html>
