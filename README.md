# jordanpartridge.us
## My Personal Website, Portfolio, Blog, and Other Adventures

Welcome to my personal website, where I showcase my professional work, hobbies, and adventures. This site is built with a focus on clean design, robust performance, and seamless integration of various technologies.

## Technologies
- **Laravel**: A powerful PHP framework for building web applications.
- **Tailwind CSS**: A utility-first CSS framework for creating custom designs directly in your markup.
- **Livewire**: A full-stack framework for Laravel that makes building dynamic interfaces simple, without leaving the comfort of Laravel.

## Features
- **Professional Portfolio**: Highlighting my work and projects.
- **Blog**: Sharing insights, tutorials, and personal experiences.
- **Strava Integration**: Automatically syncing my biking adventures from Strava every hour using Laravel's scheduler.
- **GitHub Repository**: Access the complete codebase of this site on GitHub.

## Deployment Status
[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F1fcb3f58-585a-453a-8a5c-d4af80bf60f0%3Fdate%3D1%26commit%3D1&style=plastic)](https://forge.laravel.com/servers/820904/sites/2398933)

## Continuous Integration
[![Pint](https://github.com/jordanpartridge/jordanpartridge.us/actions/workflows/Pint.yml/badge.svg?branch=master)](https://github.com/jordanpartridge/jordanpartridge.us/actions/workflows/Pint.yml)
[![Tests](https://github.com/jordanpartridge/jordanpartridge.us/actions/workflows/Tests.yml/badge.svg)](https://github.com/jordanpartridge/jordanpartridge.us/actions/workflows/Tests.yml)

## Getting Started
Clone the repository and install the dependencies:
```bash
git clone https://github.com/jordanpartridge/jordanpartridge.us.git
cd jordanpartridge.us
composer install
npm install
npm run dev
```
## Code Quality and Formatting

This project uses [Duster](https://github.com/tightenco/duster) to maintain code quality and consistency across the codebase.

This will automatically fix most issues; however, if you prefer to run it manually, you can use the following command:

```bash
./vendor/bin/duster fix
```

Also, committing to the repo will automatically run the tests and linting.
