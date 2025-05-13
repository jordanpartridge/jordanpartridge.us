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