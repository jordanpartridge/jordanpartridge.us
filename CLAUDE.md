# CLAUDE.md - Essential Commands and Guidelines

## Build & Development Commands
- Development: `npm run dev`
- Production Build: `npm run build`
- Database Migration: `php artisan migrate`
- Site is on herd locally and can be seen in browser at jordanpartridge.test

## Test Commands
- Run all tests: `php artisan test` or `./vendor/bin/pest`
- Run single test: `php artisan test --filter=TestName` or `./vendor/bin/pest tests/Path/To/TestFile.php`
- Run feature tests: `./vendor/bin/pest tests/Feature`
- Run unit tests: `./vendor/bin/pest tests/Unit`
- Run browser tests: `php artisan dusk`

## Linting & Code Quality

- Fix code style: `./vendor/bin/duster fix`
- Run Pint: `./vendor/bin/pint`
- Generate IDE helper files: `php artisan ide-helper:generate`

## Gmail Integration

### Authentication Setup

- Authentication uses Google OAuth 2.0 protocol
- Access tokens expire after 1 hour, refresh tokens are used automatically
- Ensure your Google Cloud project is set to "In production" for longer refresh token validity
- Required OAuth scopes are defined in `config/gmail-client.php`

### Token Management

- Tokens are stored in the `gmail_tokens` table
- Each user has one token record with access_token, refresh_token, expires_at fields
- The `CheckPendingGmailAuth` middleware handles post-authentication token storage
- The User model provides `hasValidGmailToken()` and `getGmailClient()` helpers

### Email Integration

- Client emails are stored in the `client_emails` table
- Related through the Client model via the `emails()` relationship
- Front-end display handled by custom Filament pages in the Email Management section

## Architecture Overview
This Laravel application serves as a personal website, portfolio, blog, and client management system with several key components:

1. **Client Management System**:
   - Client model with various statuses (Lead, Active, Former)
   - Document attachments and email integration with Gmail
   - Focused client feature for prioritizing work
2. **Integration Services**:
   - GitHub repositories sync
   - Strava activity integration
   - Gmail client for email tracking
3. **Admin Panel**:
   - Built with Filament v3
   - Custom dashboards and resources
   - Activity logging and reporting

4. **Content Management**:
   - Blog posts with categories
   - Social sharing functionality
   - RSS feed generation

## Key Dependencies

- Laravel v11+ with PHP 8.4+
- Filament admin panel v3
- Laravel Folio for route-by-blade file structure
- Spatie packages (activitylog, settings, login-link)
- Tailwind CSS for styling
- API integrations (Strava, GitHub, Gmail)

## Code Style Guidelines
- Follow PSR-12 code style standard
- Class names: PascalCase
- Methods/variables: camelCase
- Align arrow operators for arrays: `'key' => 'value'`
- Use single space around concatenation operator: `$var . ' string'`
- Use type hints for parameters and return types
- Use Laravel conventions for model relationships
- Handle exceptions appropriately with try/catch blocks
- Import classes explicitly (no use of aliases without imports)