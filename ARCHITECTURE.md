# Project Architecture & Documentation

## Overview
This document outlines the architecture, component structure, and integration points for jordanpartridge.us - a personal website, portfolio, blog, and client management system built with Laravel. This documentation is intended to provide a comprehensive guide to understanding and working with the codebase.

## Core Application Structure

### Applications Within the Website

The site operates as a collection of integrated applications:

1. **Personal Website & Portfolio**
   - Home page with professional information
   - Portfolio showcasing development skills
   - Services and work-with-me sections

2. **Blog Platform**
   - Content management with post categories
   - Social sharing functionality
   - Featured post designation

3. **Bike Activity Tracker**
   - Strava API integration
   - Ride metrics visualization
   - Customized UI/UX for cycling theme

4. **Client Management System**
   - Client data tracking
   - Document management
   - Gmail integration for email tracking

5. **Admin Interface**
   - Built with Filament v3
   - Custom dashboards and resource management
   - Fine-grained access control

### Key Technologies

- **Core Framework**: Laravel 12+
- **PHP Version**: 8.4+
- **Frontend**: Tailwind CSS, Alpine.js, Livewire (TALL Stack)
- **Admin Panel**: Filament v3
- **Routing**: Laravel Folio (route-by-blade file structure)
- **Package Ecosystem**: Spatie packages (activitylog, settings, login-link)
- **API Integrations**: Strava, GitHub, Gmail
- **Database**: MySQL

## Component Architecture

### View Components Structure

The application uses a hierarchical component structure for views:

```
resources/views/
├── components/      # Reusable view components
│   ├── bike-joy/    # Bike section components
│   ├── home/        # Home page components
│   ├── layouts/     # Layout templates
│   ├── software-development/ # Developer section components
│   ├── ui/          # Base UI components
├── livewire/        # Livewire component templates
├── pages/           # Folio page templates
    ├── bike/        # Bike section pages
    ├── blog/        # Blog section pages
```

### Component Best Practices

1. **Component Encapsulation**
   - Each component should have a single responsibility
   - Components should be reasonably self-contained
   - Props should be used for passing data into components

2. **Component Hierarchy**
   - Base UI components (ui/) provide foundational elements
   - Feature-specific components build on these base components
   - Page templates compose multiple components together

3. **Layout Components**
   - The `layouts/` directory contains wrapper layouts
   - Layouts handle document structure, meta information, and shared elements
   - Feature sections use appropriate layouts based on context

## API Integrations

### GitHub Integration

The GitHub integration showcases repositories on the website and syncs repository data:

1. **Service Layer**
   - `GitHubService` handles repository fetching and syncing
   - `GitHubSyncService` manages periodic updates
   - These services use Laravel's built-in HTTP client to interact with GitHub's API

2. **Models**
   - `GithubRepository` model stores repository data
   - Repositories can be marked as featured for homepage display

3. **Components**
   - `github-repositories.blade.php` for displaying repository lists
   - `github-repo-card.blade.php` for individual repository display

4. **Configuration**
   - GitHub settings are managed via the `GitHubSettings` class

### Strava Integration

Connects to Strava's API to fetch and display cycling activities:

1. **Service Layer**
   - `RideMetricService` processes ride data for display
   - Automatic syncing via scheduled command `SyncActivities`

2. **Models**
   - `Ride` model stores activity data from Strava
   - Event-driven architecture with `RideSynced` events

3. **Components**
   - Specialized bike activity components in `components/bike-joy/`
   - Custom styling with biking theme

### Gmail Integration

Integrates with Gmail API for email tracking and management:

1. **Package Integration**
   - Custom package `partridgerocks/gmail-client`
   - OAuth 2.0 authentication flow

2. **Models**
   - `GmailToken` for storing authentication tokens
   - `ClientEmail` for tracking client-related emails

3. **Components**
   - Custom Filament pages for email management
   - Admin interface for Gmail configuration

## Page Structure

### Laravel Folio Implementation

The site uses Laravel Folio for route-by-blade file structure, allowing direct routing from blade files:

```
resources/views/pages/
├── bike/                   # Bike section pages
│   ├── index.blade.php     # Main bike dashboard
│   ├── rides/              # Ride detail pages
│   │   ├── [Ride:id].blade.php  # Dynamic ride detail page
├── blog/                   # Blog section
│   ├── index.blade.php     # Blog listing page
│   ├── [Post:slug].blade.php    # Dynamic post page
├── index.blade.php         # Home page
```

### Dynamic Routing

1. **Parameter Routes**
   - Dynamic routes use the `[Parameter:attribute]` naming convention
   - Examples: `[Post:slug]`, `[Ride:id]`, `[category:slug]`

2. **Route Organization**
   - Routes are organized by feature/section
   - Each section has its own directory structure

3. **Middleware Files**
   - Middleware can be applied to sections using `_middleware.php` files
   - Used for authentication, authorization, and feature-specific middleware

## Livewire Components

### Key Livewire Components

1. **SocialShare**
   - Enables social sharing functionality with tracking
   - Supports multiple platforms (Twitter, LinkedIn, Facebook)
   - Tracks share counts and user interactions

2. **Volt Components**
   - Blog listing uses Livewire Volt components
   - Bike metrics and filtering uses Volt for reactivity
   - Pagination and filtering handled via Volt state

### Volt Implementation

Volt is used for simplified Livewire components directly in Blade files:

```php
@volt('component-name')
<div>
    <!-- Component template -->
</div>
@endvolt
```

## Admin Dashboard (Filament)

### Custom Resources

1. **Client Management**
   - `ClientResource` with custom actions and relation managers
   - Document attachment handling
   - Activity logging

2. **Post Management**
   - `PostResource` with rich text editing
   - Category management
   - Social sharing analytics

3. **GitHub Management**
   - Repository synchronization
   - Repository featuring for homepage

### Custom Pages

1. **Gmail Integration Pages**
   - OAuth configuration
   - Email browsing and filtering
   - Label management

2. **Analytics Dashboards**
   - Custom widgets for metrics
   - Client activity tracking
   - Ride statistics

## Tailwind & UI Framework

### Custom UI Components

1. **Base UI Components**
   - Button, badge, modal, and form components
   - Dark mode support with toggle functionality
   - Responsive design patterns

2. **Marketing Components**
   - Hero sections with animations
   - Feature showcases
   - Call to action components

3. **Application Components**
   - Specialized components for application UI
   - Dashboard layouts and widgets

### Style Configuration

- Custom Tailwind theme configuration
- Brand color system
- Responsive breakpoints
- Dark mode implementation

## Authentication & Authorization

### Authentication

- Laravel's built-in authentication
- Filament authentication customization
- Password-less login options via Spatie Login Link

### Authorization

- Role and permission management
- Feature-specific access control
- Administrative boundaries

## Data Flow & State Management

### Event-Driven Architecture

- Events for key actions (e.g., RideSynced)
- Event listeners for side effects
- Activity logging via events

### Caching Strategy

- Query result caching
- API response caching
- Fragment caching for performance

## Testing Strategy

### Test Categories

1. **Feature Tests**
   - End-to-end functionality testing
   - Route testing
   - Controller behavior verification

2. **Unit Tests**
   - Service layer logic
   - Model relationship testing
   - Helper function verification

3. **Browser Tests**
   - UI component interactions
   - JavaScript functionality
   - User flow validation

### Test Commands

- Standard test suite: `php artisan test` or `./vendor/bin/pest`
- Browser tests: `php artisan dusk`
- Group-specific tests: `./vendor/bin/pest tests/Feature`

## Deployment

### Deployment Pipeline

1. **Code Quality Checks**
   - Linting with Laravel Pint/Duster
   - Static analysis with PHPStan
   - Automated tests via GitHub Actions

2. **Laravel Forge Deployment**
   - Automatic deployment from GitHub
   - Dependency installation
   - Database migrations
   - Cache warming

### Environment Configuration

- Environment-specific configurations
- Secret management
- API key handling

## Performance Optimization

### Key Optimizations

1. **Database Optimizations**
   - Eager loading relationships
   - Indexing strategy
   - Query optimization

2. **Asset Management**
   - Vite-based asset compilation
   - CSS and JS minification
   - Image optimization

3. **Caching Strategy**
   - View caching
   - Route caching
   - Configuration caching

## Development Workflow

### Local Development

1. **Environment Setup**
   - PHP 8.4+ with required extensions
   - Composer for dependencies
   - Node.js and npm for frontend assets

2. **Development Commands**
   - `npm run dev` for asset compilation with HMR
   - `php artisan migrate` for database migrations
   - `./vendor/bin/duster fix` for code style fixing

### Code Quality Tools

- Laravel Pint for PHP formatting
- Duster for combined linting and fixing
- IDE Helper for better IDE integration

## Contributing Guidelines

### Code Style

- PSR-12 code style standard
- Class naming: PascalCase
- Method/variable naming: camelCase
- Type hinting and return types

### Pull Request Process

1. Create feature branches from `master`
2. Ensure tests pass and code quality checks succeed
3. Submit PR with descriptive title and explanation
4. Address review feedback
5. Merge to `master` when approved

## Upgrades & Migrations

The project has been upgraded to Laravel 12, which includes several key changes:

1. **PHP 8.2+ Requirement**
   - Updated type hinting and return types
   - New PHP 8.2+ features usage

2. **Package Updates**
   - Laravel Prompts has been updated to match Laravel 12 requirements
   - Various dependency updates for compatibility

3. **Middleware Implementations**
   - New _middleware.php files have been added for route grouping
   - Middleware isolation by feature section

## Future Development Plans

### Planned Enhancements

1. **Component Improvements**
   - Additional extraction of reusable components
   - Enhanced component prop typing
   - Component documentation

2. **Integration Expansion**
   - Additional third-party integrations
   - API expansion for client applications
   - Webhook handler improvements

3. **Performance Optimizations**
   - Further caching implementations
   - Advanced query optimization
   - Asset delivery improvements

## Troubleshooting

### Common Issues

1. **Asset Compilation Issues**
   - Ensure Node.js and npm are properly installed
   - Check for Vite configuration issues
   - Verify asset paths are correct

2. **API Integration Problems**
   - Verify API credentials are correctly set
   - Check for rate limiting issues
   - Examine API response logs

3. **Database Migration Failures**
   - Check database connection settings
   - Verify migration files for errors
   - Use rollback and migrate commands for clean slate

### Logging and Monitoring

- Review Laravel logs at `storage/logs/laravel.log`
- Monitor queue workers with `ps aux | grep artisan`
- Use database seeders for test data population

## Conclusion

This architecture document provides a comprehensive overview of the jordanpartridge.us website and its components. By following the patterns and practices outlined here, developers can maintain consistency and quality while extending the application's functionality.

For specific code references, always consult the inline documentation and code comments for the most up-to-date information.