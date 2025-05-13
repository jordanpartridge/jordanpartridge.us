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

Note: Some tests require frontend assets. Run `npm run build` before running tests if you encounter asset-related failures.

## Linting & Code Quality

- Fix code style: `./vendor/bin/duster fix`
- Run Pint: `./vendor/bin/pint`
- Generate IDE helper files: `php artisan ide-helper:generate`

## Production Server Management

### SSH Access

```bash
# Connect to the production server
ssh forge@jordanpartridge.us

# Navigate to the site directory
cd /home/forge/jordanpartridge.us
```

### Logs & Monitoring

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Filter logs for errors
grep -i error storage/logs/laravel.log

# Filter logs for specific features
grep -i 'gmail\|google' storage/logs/laravel.log

# Check PHP-FPM status (requires sudo)
# Note: Forge user cannot run sudo commands directly
# This must be done through the Forge dashboard
```

### Deployment

- Deployments are managed through Laravel Forge
- Changes pushed to `master` branch trigger automatic deployment
- Deployment scripts run:
  1. Pull latest changes from git
  2. Install composer dependencies
  3. Run migrations
  4. Cache configuration
  5. Restart relevant services

### Troubleshooting

If you encounter issues after deployment:

1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Verify environment variables in `.env`
3. Check queue workers: `ps aux | grep artisan`
4. Verify package installation: `composer show | grep package-name`
5. Clear all caches:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
```

6. For permission issues:
   - Files should be owned by forge:forge
   - Storage/cache directories need to be writable

7. If a package is missing, run:
   ```bash
   php -d memory_limit=-1 composer update package/name --with-dependencies
   ```

## Gmail Integration

### Authentication Setup

- Authentication uses Google OAuth 2.0 protocol
- Access tokens expire after 1 hour, refresh tokens are used automatically
- Ensure your Google Cloud project is set to "In production" for longer refresh token validity
- Required OAuth scopes are defined in `config/gmail-client.php`

### Gmail Client Package Installation

The Gmail client integration uses a custom package `partridgerocks/gmail-client` that must be properly installed:

#### Local Development

1. Clone the package repository:
```bash
# Clone into a sibling directory to your main project
cd ~/Sites
git clone git@github.com:partridgerocks/gmail-client.git
cd gmail_client
git checkout feature/gmail-api-integration
```

2. Configure your main project to use the local package:
```json
// In composer.json
"repositories": [
    {
        "type": "path",
        "url": "../gmail_client"
    }
],
"require": {
    "partridgerocks/gmail-client": "dev-feature/gmail-api-integration"
}
```

3. Install the package:
```bash
composer update partridgerocks/gmail-client
```

#### Production Deployment

For production, the package must be available on GitHub:

1. Ensure the repository exists at github.com/partridgerocks/gmail-client
2. The repository must have the branch `feature/gmail-api-integration`
3. The repository must be public or accessible to the deployment server
4. Configure composer.json with:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/partridgerocks/gmail-client"
    }
],
"require": {
    "partridgerocks/gmail-client": "dev-feature/gmail-api-integration"
}
```

5. Verify installation on production:
```bash
ssh forge@jordanpartridge.us
cd /home/forge/jordanpartridge.us
ls -la vendor/partridgerocks/gmail-client
```

If the package is missing on production, manually install it:
```bash
php -d memory_limit=-1 composer update partridgerocks/gmail-client --with-dependencies
```

### Google Cloud Project Setup

1. Create a project at [Google Cloud Console](https://console.cloud.google.com/)
2. Enable the Gmail API in API Library
3. Configure OAuth consent screen:
   - Set User Type to "External"
   - Add authorized domains
   - Add scopes for Gmail API (read-only is the minimum)
   - Add test users if in testing mode
4. Create OAuth credentials:
   - Application type: Web application
   - Add authorized redirect URIs (e.g., https://yourdomain.com/gmail/callback)
   - Save client ID and client secret to your environment variables

### Environment Configuration

Add these variables to your `.env` file:
```
GMAIL_CLIENT_ID=your-client-id
GMAIL_CLIENT_SECRET=your-client-secret
GMAIL_REDIRECT_URI=https://yourdomain.com/gmail/callback
GMAIL_FROM_EMAIL=your@gmail.com
```

### Token Management

- Tokens are stored in the `gmail_tokens` table
- Each user has one token record with access_token, refresh_token, expires_at fields
- The `CheckPendingGmailAuth` middleware handles post-authentication token storage
- The User model provides `hasValidGmailToken()` and `getGmailClient()` helpers
- Token refresh happens automatically when expired (if refresh token is available)

### Email Integration

- Client emails are stored in the `client_emails` table
- Related through the Client model via the `emails()` relationship
- Front-end display handled by custom Filament pages in the Email Management section
- Email components:
  - GmailIntegrationPage: Main admin page for OAuth setup
  - GmailLabelsPage: View and manage Gmail labels
  - GmailMessagesPage: Browse recent Gmail messages

### Testing OAuth Locally

1. Use a tunneling service like [Expose](https://expose.dev/) or [ngrok](https://ngrok.com/)
2. Configure the tunnel to point to your local development server
3. Add the tunnel URL as an authorized redirect URI in Google Cloud Console
4. Update your local `.env` with the tunnel URL as GMAIL_REDIRECT_URI
5. Run the tunnel with `expose share http://localhost:8000`

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