const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    darkMode: 'class',
    // Add safelist configuration to protect dynamic classes from being purged
    safelist: [
        // Timeline component colors
        {
            pattern: /(bg|text|border)-(primary|secondary|blue|indigo|teal|green|purple|pink|gray)-(50|100|200|300|400|500|600|700|800|900|950)/,
            variants: ['dark', 'hover', 'group-hover', 'focus', 'active']
        },
        // Opacity and translation classes for timeline
        {
            pattern: /(opacity|translate)-(0|50|100)/,
            variants: ['dark', 'hover', 'group-hover', 'focus']
        },
        // Preserve dark mode variants of timeline classes
        {
            pattern: /dark:bg-(primary|secondary|blue|indigo|teal|green|purple|pink|gray)-(50|100|200|300|400|500|600|700|800|900|950)\/[0-9]+/,
        }
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                    950: '#082f49',
                },
                secondary: {
                    50: '#f0fdfa',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#14b8a6',
                    600: '#0d9488',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#134e4a',
                    950: '#042f2e',
                },
            },
            animation: {
                'float': 'float 3s ease-in-out infinite',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            },
        },
    },
    variants: {
        extend: {
            backgroundColor: ['active'],
            opacity: ['dark'],
            transform: ['hover', 'focus'],
        },
    },
    content: ['./app/**/*.php', './resources/**/*.{php,html,js,jsx,ts,tsx,vue,twig}'],
    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
}
