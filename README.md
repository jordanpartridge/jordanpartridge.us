# jordanpartridge.us
## My Personal Website, Portfolio, Blog, and Other Adventures

Welcome to my personal website, where I showcase my professional work, hobbies, and adventures. This site is built with a focus on clean design, robust performance, and seamless integration of various technologies.

## Technologies
- **Laravel**: A powerful PHP framework for building web applications.
- **Tailwind CSS**: A utility-first CSS framework for creating custom designs directly in your markup.
- **Livewire**: A full-stack framework for Laravel that makes building dynamic interfaces simple, without leaving the comfort of Laravel.
- **Strava API**: Bike rides synced to site utilizing Laravel's Scheduler to check for new rides hourly
- **Folio**: Route by blade file structure
- **Filament**: Livewire powered Admin Panel
- **Spatie/Activitylog**: Log all the things that happen on the site.

## Features
- **Professional Portfolio**: Highlighting my work and projects.
- **Blog**: Sharing insights, tutorials, and personal experiences.
- **Strava Integration**: Automatically syncing my biking adventures from Strava every hour using Laravel's scheduler.
- **GitHub Repository**: Access the complete codebase of this site on GitHub.

## Deployment Status
[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F1fcb3f58-585a-453a-8a5c-d4af80bf60f0%3Fdate%3D1%26label%3D1%26commit%3D1&style=plastic)](https://forge.laravel.com/servers/820904/sites/2398933)

## Waka Time
[![wakatime](https://wakatime.com/badge/user/af39b85c-9dd3-45aa-a975-04ca41a569a7/project/8d750652-7330-42a5-8fab-2a38e85c329f.svg)](https://wakatime.com/badge/user/af39b85c-9dd3-45aa-a975-04ca41a569a7/project/8d750652-7330-42a5-8fab-2a38e85c329f)

## Continuous Integration
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

## Card API

This site uses jordanpartridge/card-api for Blackjack; please ensure your environment is set up.

```dotenv
CARD_API_URL=https://card-api.jordanpartridge.us
CARD_API_KEY=some-secret-key
```

Running Duster will automatically fix most issues. However, if you prefer to run it manually, you can use the following command:

```bash
./vendor/bin/duster fix
```

Also, committing to the repo will automatically run the tests and linting.
