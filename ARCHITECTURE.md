# Architecture Documentation

## Overview

This Laravel application serves as a personal website, portfolio, blog, and client management system. The site is built on Laravel 11+ and uses various modern web technologies and third-party integrations to create a comprehensive digital presence.

## Technology Stack

- **Backend Framework**: Laravel 11+ with PHP 8.4+
- **Frontend**: 
  - TailwindCSS for styling
  - Alpine.js for interactive components
  - Blade templates for view rendering
- **Database**: MySQL
- **Admin Panel**: Filament v3
- **Routing**: Laravel Folio for route-by-blade file structure
- **Queue System**: Laravel Horizon
- **API Integrations**:
  - Strava API for cycling activity data
  - GitHub API for repository information
  - Gmail API for email tracking and management

## Core Components

### 1. Client Management System

The application includes a comprehensive client management system with:

- Client profiles with various statuses (Lead, Active, Former)
- Document management for client files
- Email integration with Gmail
- Activity logging and tracking
- Focus mode for prioritizing specific clients

```php
// Example Client Model Relationships
public function documents(): HasMany
{
    return $this->hasMany(ClientDocument::class);
}

public function emails(): HasMany
{
    return $this->hasMany(ClientEmail::class);
}
```

### 2. Content Management

The site serves as a personal portfolio and blog with:

- Blog posts with categories and tags
- Portfolio projects and case studies
- Social sharing functionality
- RSS feed generation

```php
// Example Post Model Relationships
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}

public function socialShares(): HasMany
{
    return $this->hasMany(SocialShare::class);
}
```

### 3. Activity Tracking

Integration with Strava API to display cycling activities:

- Synchronization of ride data
- Display of ride metrics and statistics
- Interactive charts and maps
- Automated notifications for new rides

### 4. Admin Interface

Built with Filament v3, providing:

- Custom dashboards and analytics
- Resource management for all models
- Activity logging and reporting
- Settings management
- Role-based access control

## Directory Structure

### Laravel Standard Directories

- `app/` - Application code
  - `Console/` - Console commands
  - `Http/` - Controllers, middleware, requests
  - `Models/` - Eloquent models
  - `Providers/` - Service providers
  - `Services/` - Service classes
  - `View/` - View composers and components
- `config/` - Configuration files
- `database/` - Migrations, seeders, and factories
- `resources/` - Frontend assets and views
- `routes/` - Route definitions
- `storage/` - Logs, cache, and uploads
- `tests/` - Test cases

### Custom Directories

- `app/Filament/` - Filament admin panel resources
- `app/Livewire/` - Livewire components
- `app/Services/` - Service classes for business logic
- `app/Enums/` - Enumeration classes
- `app/Facades/` - Facade classes
- `app/Settings/` - Settings classes
- `app/Observers/` - Model observers

## Data Flow and Communication

### Request Lifecycle

1. Request enters through public/index.php
2. Laravel routes the request (via web.php or Folio page routes)
3. Middleware processes the request (authentication, etc.)
4. Controller or route handler processes the request
5. Response is returned to the user

### Integration with External Services

#### Strava Integration

- `SyncActivitiesJob` fetches recent activities from Strava API
- Activities are processed and stored in the `rides` table
- `RideMetricService` calculates additional metrics
- `RideObserver` triggers notifications when rides are updated

#### GitHub Integration

- GitHub repositories are synced through `GitHubSettings`
- Repository data is stored in the `github_repositories` table
- Webhook integration allows for real-time updates

#### Gmail Integration

- OAuth2 authentication with Google
- Email data is fetched and stored in `client_emails` table
- `GmailAuthServiceProvider` handles authentication and token refresh

## Security Implementation

- User authentication via Laravel Sanctum and Login Links
- Role and permission management with Spatie's permission package
- CSRF protection via Laravel's built-in CSRF middleware
- Request validation for all form submissions
- Secure API token storage
- Rate limiting on sensitive endpoints
- Audit logging for important actions

## Testing Strategy

- Feature tests for critical user journeys
- Unit tests for complex business logic
- Browser tests with Laravel Dusk
- Integration tests for API endpoints

## Deployment and Environments

- **Local Development**: Laravel Herd or Laravel Sail
- **Production**: Laravel Forge with automatic deployments
- **Monitoring**: Laravel Pulse
- **Queue Processing**: Laravel Horizon

## Scheduled Tasks

- Daily sync with Strava API
- Weekly cleanup of expired tokens
- Monthly analytics reports

## Performance Optimization

- Database query optimization through eager loading
- Cache implementation for frequently accessed data
- Queue system for background processing
- Asset optimization with Vite

## Future Architecture Considerations

- Migration to Laravel Octane for improved performance
- Implementation of microservices for specific integrations
- Enhanced telemetry and monitoring
- API-first approach for mobile applications