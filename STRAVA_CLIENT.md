# Laravel Strava Client Package

## Overview

The Laravel Strava Client is a comprehensive package designed to seamlessly integrate Strava API functionality into Laravel applications. This package provides developers with tools to authenticate, retrieve, and manipulate Strava activity data through an elegant and Laravel-friendly interface.

**Key Features:**
- OAuth 2.0 authentication flow for Strava API
- Activity retrieval and synchronization
- Detailed ride metrics processing
- Clean data transformation between API and application models
- Caching strategies for improved performance
- Laravel-native integration with event system

## Installation

```bash
composer require jordanpartridge/strava-client
```

## Configuration

After installing the package, publish the configuration file:

```bash
php artisan vendor:publish --tag=strava-client-config
```

Configure your Strava API credentials in your `.env` file:

```env
STRAVA_CLIENT_ID=your-client-id
STRAVA_CLIENT_SECRET=your-client-secret
STRAVA_REDIRECT_URI=https://yourdomain.com/strava/callback
```

## Basic Usage

### Authentication

The package handles the OAuth 2.0 authentication flow with Strava. To initiate the authentication process:

```php
use JordanPartridge\StravaClient\Facades\Strava;

// Redirect the user to Strava for authorization
return Strava::redirectToAuthorizationPage();
```

In your callback route, handle the authorization response:

```php
use JordanPartridge\StravaClient\Facades\Strava;

public function handleCallback(Request $request)
{
    // Exchange authorization code for access token
    $tokens = Strava::handleAuthorizationCallback($request->code);
    
    // Store tokens for the authenticated user
    auth()->user()->stravaTokens()->updateOrCreate(
        ['user_id' => auth()->id()],
        [
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'expires_at' => now()->addSeconds($tokens['expires_in']),
        ]
    );
    
    return redirect()->route('dashboard')->with('success', 'Strava account connected!');
}
```

### Fetching Activities

Once authenticated, you can fetch a user's activities:

```php
use JordanPartridge\StravaClient\Facades\Strava;

// Get recent activities
$activities = Strava::activities()->get();

// Get activities with filters
$activities = Strava::activities()
    ->after(now()->subDays(30))
    ->before(now())
    ->perPage(50)
    ->get();
```

### Syncing Activities to Your Database

The package provides a convenient way to sync Strava activities to your application's database:

```php
use JordanPartridge\StravaClient\Facades\Strava;
use App\Models\Ride;

public function syncActivities()
{
    $activities = Strava::activities()->get();
    
    foreach ($activities as $activity) {
        Ride::updateOrCreate(
            ['external_id' => $activity['id']],
            [
                'name' => $activity['name'],
                'distance' => $activity['distance'],
                'moving_time' => $activity['moving_time'],
                'elapsed_time' => $activity['elapsed_time'],
                'elevation' => $activity['total_elevation_gain'],
                'date' => Carbon::parse($activity['start_date']),
                'average_speed' => $activity['average_speed'],
                'max_speed' => $activity['max_speed'],
                'calories' => $activity['calories'] ?? 0,
                'polyline' => $activity['map']['summary_polyline'] ?? null,
            ]
        );
    }
}
```

## Advanced Usage

### Automatic Token Refresh

The package automatically handles token refreshing. Implement the `HasStravaTokens` trait in your User model:

```php
use JordanPartridge\StravaClient\Traits\HasStravaTokens;

class User extends Authenticatable
{
    use HasStravaTokens;
    
    // ...
}
```

### Webhook Integration

Set up a Strava webhook to receive real-time updates:

```php
use JordanPartridge\StravaClient\Facades\Strava;

// Register webhook
Strava::webhooks()->register(
    route('strava.webhook'),
    'your-verification-token'
);

// Handle webhook notifications
Route::post('/strava/webhook', function (Request $request) {
    $event = $request->all();
    
    if ($event['object_type'] === 'activity' && $event['aspect_type'] === 'create') {
        // Dispatch job to process new activity
        SyncNewActivityJob::dispatch($event['owner_id'], $event['object_id']);
    }
    
    return response()->json(['status' => 'success']);
});
```

### Caching Strategies

The package implements smart caching to reduce API calls:

```php
use JordanPartridge\StravaClient\Facades\Strava;

// With custom cache duration
$activities = Strava::activities()
    ->cache(60 * 24) // Cache for 24 hours
    ->get();
    
// Force fresh data
$activities = Strava::activities()
    ->fresh()
    ->get();
```

## Practical Implementation Example

The "Bike Joy" page on jordanpartridge.us demonstrates a complete implementation of the Strava Client package:

1. **Authentication Flow**: Secure OAuth connection to Strava
2. **Activity Synchronization**: Regular syncing of new rides
3. **Metric Processing**: Calculation of aggregated statistics
4. **Data Presentation**: Interactive display of ride data and maps

The implementation employs these key components:

```php
// SyncActivitiesJob.php
public function handle(StravaClient $stravaClient)
{
    $activities = $stravaClient->activities()->get();
    
    foreach ($activities as $activity) {
        // Convert Strava API data to application model
        $this->processActivity($activity);
    }
    
    // Clear cached metrics to ensure fresh data
    app(RideMetricService::class)->clearCache();
}

// RideMetricService.php
public function calculateRideMetrics($startDate, $endDate): array
{
    $cacheKey = "ride_metrics:{$startDate}:{$endDate}";
    
    return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($startDate, $endDate) {
        $rides = Ride::whereBetween('date', [$startDate, Carbon::parse($endDate)->addDay()])->get();
        
        return [
            $rides,
            [
                'distance'      => $rides->sum('distance'),
                'calories'      => $rides->sum('calories'),
                'elevation'     => $rides->sum('elevation'),
                'ride_count'    => $rides->count(),
                'max_speed'     => $rides->max('max_speed'),
                'average_speed' => $rides->count() > 0 ? number_format($rides->avg('average_speed'), 1) : 0,
            ],
            $startDate,
            $endDate,
        ];
    });
}
```

## Best Practices

1. **Token Security**: Always store Strava tokens securely, preferably encrypted
2. **Rate Limiting**: Be mindful of Strava's API rate limits (100 requests per 15 minutes)
3. **Webhook Validation**: Verify webhook requests come from Strava
4. **Background Processing**: Use Laravel jobs for syncing activities
5. **Error Handling**: Implement proper exception handling for API failures

## API Reference

The package provides a comprehensive set of methods to interact with the Strava API:

### Authentication
- `redirectToAuthorizationPage()`
- `handleAuthorizationCallback($code)`
- `refreshToken($refreshToken)`

### Activities
- `activities()->get()`
- `activities()->find($id)`
- `activities()->after($timestamp)`
- `activities()->before($timestamp)`
- `activities()->perPage($count)`

### Athletes
- `athlete()->get()`
- `athlete()->stats()`
- `athlete()->zones()`

### Streams
- `streams()->get($activityId, $types)`

## Troubleshooting

### Common Issues

1. **Authentication Failures**
   - Verify client ID and secret
   - Check that redirect URI matches exactly with Strava settings

2. **Missing Activity Data**
   - Ensure proper scope during authorization
   - Check if activities are set to private on Strava

3. **Token Expiration**
   - Verify refresh token flow is implemented
   - Check token expiration times

## Case Study: The Bike Joy Page

The Bike Joy page on jordanpartridge.us serves as a real-world implementation of the Strava Client package. Key features include:

1. **Dynamic Dashboard**: Shows aggregated metrics for selected date ranges
2. **Activity List**: Displays individual rides with maps and details
3. **Automatic Updates**: New rides appear shortly after they're recorded in Strava
4. **Performance Optimization**: Cached metrics reduce load times
5. **Responsive Design**: Works seamlessly on mobile and desktop

This implementation demonstrates how the Strava Client package can be used to create engaging, data-rich experiences that showcase athletic achievements while maintaining excellent performance.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The Laravel Strava Client is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).