# Laravel 12 Upgrade Guide

## Current Status

We are in the process of upgrading from Laravel 11.46 to Laravel 12. This branch contains the necessary changes to upgrade to Laravel 12.

## Known Issues

- **Strava Client Dependency**: The `jordanpartridge/strava-client` package needs to be updated to support Laravel 12. There is an open issue (#16) for this at https://github.com/jordanpartridge/strava-client/issues/16. Once that's resolved, the package will properly support Laravel 12.

## Changes Made

### 1. Dependency Updates

- Updated Laravel Framework to `^12.0`
- Updated Laravel Prompts to `^0.3.0` (Required for Laravel 12)
- Updated Laravel Octane to `^2.9.3` (Compatible with Laravel 12)
- Updated hirethunk/verbs to `^0.6.4` (Fixed compatibility with Laravel 12)

### 2. Middleware Implementation

- Restored middleware files with Laravel 12 Folio compatibility
- Updated middleware function signature to adhere to Laravel 12 conventions
- Changed `$next($request)` to `next($request)` for Laravel 12 middleware

## Steps to Complete

1. **Resolve Package Dependencies**
   - Update the `jordanpartridge/strava-client` package to support Laravel 12 (see issue #16)
   - Once the Strava client is updated, the composer dependency conflicts should be resolved

2. **Test Compatibility**
   - Run `composer update` to update all dependencies
   - Fix any remaining dependency conflicts
   - Test the application with Laravel 12

3. **Update Additional Dependencies**
   - Check compatibility of remaining packages with Laravel 12
   - Update as needed

3. **Frontend Building**
   - Run `npm install` to update frontend dependencies
   - Use `npm run build` to compile assets

## Resources

- [Official Laravel 12 Upgrade Guide](https://laravel.com/docs/12.x/upgrade)
- [Laravel Folio Documentation](https://laravel.com/docs/12.x/folio)