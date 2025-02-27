# CLAUDE.md - Essential Commands and Guidelines

## Build & Development Commands
- PHP Server: `php artisan serve`
- Development: `npm run dev`
- Production Build: `npm run build`
- Database Migration: `php artisan migrate`

## Test Commands
- Run all tests: `php artisan test` or `./vendor/bin/pest`
- Run single test: `php artisan test --filter=TestName` or `./vendor/bin/pest tests/Path/To/TestFile.php`
- Run feature tests: `./vendor/bin/pest tests/Feature`
- Run unit tests: `./vendor/bin/pest tests/Unit`

## Linting & Code Quality
- Fix code style: `./vendor/bin/duster fix`
- Run Pint: `./vendor/bin/pint`

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