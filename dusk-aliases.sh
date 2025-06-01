#!/bin/bash

# Laravel Dusk Testing Aliases
# Source this file: source dusk-aliases.sh

# Watch tests run with visible browser
alias dusk-watch='DUSK_HEADLESS_DISABLED=true DUSK_START_MAXIMIZED=true php artisan dusk'

# Run specific test with visible browser
alias dusk-debug='DUSK_HEADLESS_DISABLED=true DUSK_START_MAXIMIZED=true php artisan dusk'

# Fast headless testing (like CI)
alias dusk-fast='php artisan dusk'

# Run single test in watch mode
dusk-test() {
    if [ -z "$1" ]; then
        echo "Usage: dusk-test <test-file-or-method>"
        echo "Example: dusk-test tests/Browser/CoreFunctionalityTest.php"
        return 1
    fi
    DUSK_HEADLESS_DISABLED=true DUSK_START_MAXIMIZED=true php artisan dusk "$1"
}

echo "Dusk aliases loaded!"
echo "Available commands:"
echo "  dusk-watch  - Run all tests with visible browser"
echo "  dusk-debug  - Run tests with visible browser (same as dusk-watch)"
echo "  dusk-fast   - Run tests headless (like CI)"
echo "  dusk-test   - Run specific test with visible browser"