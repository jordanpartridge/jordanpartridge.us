<?php
// Show the Strava client documentation
use function Livewire\Volt\{state};

state([]);
?>

<x-layouts.marketing>
    <div class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose dark:prose-invert prose-lg mx-auto">
                <h1 class="text-3xl font-bold mb-8 text-center">Laravel Strava Client Package</h1>

                <div class="mb-8">
                    <h2 class="text-2xl font-semibold">Overview</h2>
                    <p>
                        The Laravel Strava Client is a comprehensive package designed to seamlessly integrate Strava API
                        functionality into Laravel applications. This package provides developers with tools to authenticate,
                        retrieve, and manipulate Strava activity data through an elegant and Laravel-friendly interface.
                    </p>

                    <div class="my-4 pl-6">
                        <p class="font-bold">Key Features:</p>
                        <ul class="list-disc pl-4">
                            <li>OAuth 2.0 authentication flow for Strava API</li>
                            <li>Activity retrieval and synchronization</li>
                            <li>Detailed ride metrics processing</li>
                            <li>Clean data transformation between API and application models</li>
                            <li>Caching strategies for improved performance</li>
                            <li>Laravel-native integration with event system</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-semibold">Installation</h2>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>composer require jordanpartridge/strava-client</code></pre>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-semibold">Configuration</h2>
                    <p>After installing the package, publish the configuration file:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>php artisan vendor:publish --tag=strava-client-config</code></pre>

                    <p class="mt-4">Configure your Strava API credentials in your <code>.env</code> file:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>STRAVA_CLIENT_ID=your-client-id
STRAVA_CLIENT_SECRET=your-client-secret
STRAVA_REDIRECT_URI=https://yourdomain.com/strava/callback</code></pre>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-semibold">Basic Usage</h2>

                    <h3 class="text-xl font-semibold mt-6">Authentication</h3>
                    <p>The package handles the OAuth 2.0 authentication flow with Strava. To initiate the authentication process:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>use JordanPartridge\StravaClient\Facades\Strava;

// Redirect the user to Strava for authorization
return Strava::redirectToAuthorizationPage();</code></pre>

                    <p class="mt-4">In your callback route, handle the authorization response:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>use JordanPartridge\StravaClient\Facades\Strava;

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

    return redirect()->route('dashboard')
        ->with('success', 'Strava account connected!');
}</code></pre>
                </div>

                <div class="mb-8">
                    <h3 class="text-xl font-semibold">Fetching Activities</h3>
                    <p>Once authenticated, you can fetch a user's activities:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>use JordanPartridge\StravaClient\Facades\Strava;

// Get recent activities
$activities = Strava::activities()->get();

// Get activities with filters
$activities = Strava::activities()
    ->after(now()->subDays(30))
    ->before(now())
    ->perPage(50)
    ->get();</code></pre>
                </div>

                <div class="mb-8">
                    <h3 class="text-xl font-semibold">Syncing Activities to Your Database</h3>
                    <p>The package provides a convenient way to sync Strava activities to your application's database:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>use JordanPartridge\StravaClient\Facades\Strava;
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
}</code></pre>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-semibold">Advanced Usage</h2>

                    <h3 class="text-xl font-semibold mt-6">Automatic Token Refresh</h3>
                    <p>The package automatically handles token refreshing. Implement the <code>HasStravaTokens</code> trait in your User model:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>use JordanPartridge\StravaClient\Traits\HasStravaTokens;

class User extends Authenticatable
{
    use HasStravaTokens;

    // ...
}</code></pre>
                </div>

                <div class="mb-8">
                    <h3 class="text-xl font-semibold">Webhook Integration</h3>
                    <p>Set up a Strava webhook to receive real-time updates:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>use JordanPartridge\StravaClient\Facades\Strava;

// Register webhook
Strava::webhooks()->register(
    route('strava.webhook'),
    'your-verification-token'
);

// Handle webhook notifications
Route::post('/strava/webhook', function (Request $request) {
    $event = $request->all();

    if ($event['object_type'] === 'activity' &&
        $event['aspect_type'] === 'create') {
        // Dispatch job to process new activity
        SyncNewActivityJob::dispatch(
            $event['owner_id'],
            $event['object_id']
        );
    }

    return response()->json(['status' => 'success']);
});</code></pre>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-semibold">Example Implementation</h2>
                    <p>The "Fat Bike Corps" page on jordanpartridge.us demonstrates a complete implementation of the Strava Client package.</p>

                    <div class="mt-6 bg-gray-100 dark:bg-gray-800 p-6 rounded-md">
                        <h3 class="text-lg font-semibold mb-3">From the SyncActivitiesJob</h3>
                        <pre class="overflow-x-auto">
<code>use JordanPartridge\StravaClient\Facades\Strava;

public function handle()
{
    $activities = Strava::activities()
        ->after(now()->subDays(30))
        ->perPage(50)
        ->get();

    foreach ($activities as $activity) {
        Ride::updateOrCreate(
            ['external_id' => $activity['id']],
            [
                'name' => $activity['name'],
                'distance' => $activity['distance'],
                'moving_time' => $activity['moving_time'],
                'elevation' => $activity['total_elevation_gain'],
                'date' => Carbon::parse($activity['start_date']),
                'average_speed' => $activity['average_speed'],
                'max_speed' => $activity['max_speed'],
                'polyline' => $activity['map']['summary_polyline'] ?? null,
            ]
        );
    }

    // Clear cached metrics to ensure fresh data
    app(RideMetricService::class)->clearCache();
}</code></pre>
                    </div>
                </div>

                <div class="mt-12 mb-8 text-center">
                    <a href="https://github.com/jordanpartridge/strava-client"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.477 0 10c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.52 2.34 1.08 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V19c0 .27.16.59.67.5C17.14 18.16 20 14.42 20 10A10 10 0 0010 0z" clip-rule="evenodd" />
                        </svg>
                        View on GitHub
                    </a>
                    <a href="/bike"
                       class="ml-4 inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-biking mr-2"></i>
                        See it in action
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.marketing>