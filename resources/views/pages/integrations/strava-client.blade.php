<?php
// Show the Strava client documentation
use function Livewire\Volt\{state};

state([]);

?>

<x-layouts.marketing>
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-800 dark:to-primary-950">
        <div class="absolute inset-0 opacity-10 bg-pattern-topo"></div>
        <div class="max-w-7xl mx-auto pt-16 pb-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-4">Laravel Strava Client</h1>
                <p class="max-w-3xl mx-auto text-xl text-primary-100 mb-4">
                    A developer-friendly package for seamless Strava API integration in Laravel applications
                </p>
                <p class="max-w-2xl mx-auto text-lg text-primary-200 mb-8">
                    Built by <strong>Jordan Partridge</strong> - Laravel Integration Specialist & API Package Developer
                </p>

                <!-- Package Stats -->
                <div class="flex flex-wrap justify-center gap-4 mb-8">
                    <div class="px-4 py-2 bg-primary-700 bg-opacity-50 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-white">Laravel 8-11 Compatible</span>
                    </div>
                    <div class="px-4 py-2 bg-primary-700 bg-opacity-50 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                        <span class="text-white">MIT Licensed</span>
                    </div>
                    <div class="px-4 py-2 bg-primary-700 bg-opacity-50 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="text-white">Actively Maintained</span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap justify-center gap-4 mb-4">
                    <a href="https://github.com/jordanpartridge/strava-client" target="_blank" class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-primary-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.477 0 10c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.52 2.34 1.08 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V19c0 .27.16.59.67.5C17.14 18.16 20 14.42 20 10A10 10 0 0010 0z" clip-rule="evenodd" />
                        </svg>
                        View on GitHub
                    </a>
                    <a href="/bike" class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-biking mr-2"></i>
                        See it in Action: Fat Bike Corps
                    </a>
                    <div class="relative">
                        <div id="copy-install" onclick="copyInstallCommand()" class="inline-flex items-center px-6 py-3 border border-primary-300 rounded-md shadow-sm text-base font-medium text-white bg-primary-800 hover:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            <span id="copy-text">composer require jordanpartridge/strava-client</span>
                        </div>
                        <div id="copy-tooltip" class="hidden absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-3 py-1 bg-gray-900 text-white text-sm rounded shadow-lg">
                            Copied!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose dark:prose-invert prose-lg mx-auto">
                <h2 class="text-3xl font-bold mb-2 text-center">Why Developers Choose Laravel Strava Client</h2>
                <p class="text-center text-xl text-gray-600 dark:text-gray-400 mb-8">
                    Stop wrestling with OAuth complexity. Start building features that matter.
                </p>

                <!-- Developer Benefits Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 not-prose">
                    <div class="text-center p-6 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 rounded-xl border border-blue-200 dark:border-blue-800">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">95%</div>
                        <p class="text-gray-700 dark:text-gray-300">Less Code Required</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">No OAuth routes, controllers, or token management</p>
                    </div>
                    <div class="text-center p-6 bg-green-50 dark:bg-green-900 dark:bg-opacity-20 rounded-xl border border-green-200 dark:border-green-800">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">5 Min</div>
                        <p class="text-gray-700 dark:text-gray-300">Setup Time</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">From installation to working API calls</p>
                    </div>
                    <div class="text-center p-6 bg-purple-50 dark:bg-purple-900 dark:bg-opacity-20 rounded-xl border border-purple-200 dark:border-purple-800">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">0</div>
                        <p class="text-gray-700 dark:text-gray-300">Maintenance Required</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Automatic token refresh handles everything</p>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center gap-4 mb-8 hidden">
                    <a href="https://github.com/jordanpartridge/strava-client" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.477 0 10c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.52 2.34 1.08 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V19c0 .27.16.59.67.5C17.14 18.16 20 14.42 20 10A10 10 0 0010 0z" clip-rule="evenodd" />
                        </svg>
                        View on GitHub
                    </a>
                    <a href="/bike" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-biking mr-2"></i>
                        See it in Action: Fat Bike Corps
                    </a>
                    <a href="https://developers.strava.com/docs/reference/" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Strava API Documentation
                    </a>
                </div>

                <!-- Code Comparison Section -->
                <div class="mb-12 not-prose">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-red-50 dark:bg-red-900 dark:bg-opacity-20 rounded-lg p-6 border border-red-200 dark:border-red-800">
                            <h3 class="text-lg font-bold text-red-700 dark:text-red-400 mb-3">😓 The Traditional Way (Complex & Error-Prone)</h3>
                            <pre class="bg-white dark:bg-gray-800 p-4 rounded-md overflow-x-auto text-sm">
<code class="text-xs">// Create OAuth routes and controllers
Route::get('/oauth/redirect', [OAuthController::class, 'redirect']);
Route::get('/oauth/callback', [OAuthController::class, 'callback']);

// Manual state verification and CSRF protection
$state = Str::random(32);
session(['oauth_state' => $state]);

// Build authorization URL manually
$query = http_build_query([
    'client_id' => config('strava.client_id'),
    'redirect_uri' => route('oauth.callback'),
    'response_type' => 'code',
    'scope' => 'read,activity:read_all',
    'state' => $state,
]);

// Manual token exchange and validation
$response = Http::post('https://www.strava.com/oauth/token', [
    'client_id' => config('strava.client_id'),
    'client_secret' => config('strava.client_secret'),
    'code' => $request->code,
    'grant_type' => 'authorization_code',
]);

// Manual token refresh before every API call
if ($token->expires_at < now()) {
    $refreshResponse = Http::post('https://www.strava.com/oauth/token', [
        'client_id' => config('strava.client_id'),
        'client_secret' => config('strava.client_secret'),
        'grant_type' => 'refresh_token',
        'refresh_token' => $token->refresh_token,
    ]);
    // Update stored tokens...
}

// Manual API calls with error handling
$activities = Http::withToken($token->access_token)
    ->get('https://www.strava.com/api/v3/athlete/activities');

if ($activities->status() === 401) {
    // Token expired, need to refresh again...
}</code>
                            </pre>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900 dark:bg-opacity-20 rounded-lg p-6 border border-green-200 dark:border-green-800">
                            <h3 class="text-lg font-bold text-green-700 dark:text-green-400 mb-3">🚀 The Laravel Strava Client Way (Simple & Bulletproof)</h3>
                            <pre class="bg-white dark:bg-gray-800 p-4 rounded-md overflow-x-auto text-sm">
<code class="text-xs">// ✅ OAuth routes auto-registered - NO setup needed!
// /strava/redirect - starts OAuth flow
// /strava/callback - handles OAuth callback

// ✅ Just redirect users to start authentication
return redirect('/strava/redirect');

// ✅ Tokens automatically stored after OAuth
// Package handles the entire callback flow

// ✅ Set and forget - just make API calls!
use JordanPartridge\StravaClient\Facades\StravaClient;

// Get activities - tokens auto-refresh if expired
$activities = StravaClient::activityForAthlete(1, 30);

// Get specific activity - still auto-refreshes
$activity = StravaClient::getActivity(12345678);

// Get athlete profile - auto-refreshes too
$athlete = StravaClient::getAthlete();

// Every API call automatically:
// 1. Checks if token is expired
// 2. Refreshes token if needed (up to 3 attempts)
// 3. Updates database with new token
// 4. Makes the API call
// 5. Returns clean data

// Zero token management required! 🎉</code>
                            </pre>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-semibold">Overview</h2>
                    <p>
                        The Laravel Strava Client is a comprehensive package designed to seamlessly integrate Strava API
                        functionality into Laravel applications. Created by <strong>Jordan Partridge</strong>, a specialist in building
                        Laravel integration packages, this package provides developers with tools to authenticate, retrieve, and
                        manipulate Strava activity data through an elegant and Laravel-friendly interface.
                    </p>

                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <h3 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Part of Jordan's Integration Package Suite</h3>
                        <p class="text-blue-700 dark:text-blue-300 text-sm">
                            This is one of several Laravel integration packages built by Jordan Partridge, including Gmail Client,
                            GitHub integrations, and other API connectors. Each package follows the same philosophy:
                            <strong>zero-configuration setup with bulletproof reliability</strong>.
                        </p>
                    </div>

                    <div class="my-4 pl-6">
                        <p class="font-bold">Key Features:</p>
                        <ul class="list-disc pl-4">
                            <li><strong>Enterprise-Grade OAuth Integration:</strong> Zero-configuration setup with auto-registered routes and controllers</li>
                            <li><strong>Bulletproof Token Management:</strong> Automatic refresh with failover protection (up to 3 retry attempts)</li>
                            <li><strong>Production-Ready Reliability:</strong> Built by an integration specialist with real-world experience</li>
                            <li><strong>Laravel-Native Architecture:</strong> Follows Laravel conventions with encrypted database storage</li>
                            <li><strong>Saloon HTTP Foundation:</strong> Modern HTTP client with comprehensive error handling</li>
                            <li><strong>Seamless Model Integration:</strong> HasStravaTokens trait for effortless user relationships</li>
                            <li><strong>API Rate Limit Protection:</strong> Built-in safeguards and intelligent retry logic</li>
                            <li><strong>Professional Integration Standards:</strong> Same patterns used in Jordan's other integration packages</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-center">🚀 Quick Start Guide</h2>
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900 dark:to-blue-900 dark:bg-opacity-20 rounded-xl p-8 border border-green-200 dark:border-green-800">
                        <p class="text-lg mb-6 text-center text-gray-700 dark:text-gray-300">
                            Get up and running with Strava integration in <strong>less than 5 minutes!</strong>
                        </p>

                        <div class="space-y-8">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold mb-2">Install the Package</h3>
                                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code>composer require jordanpartridge/strava-client</code></pre>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold mb-2">Create a Strava App</h3>
                                    <p class="mb-3">Go to <a href="https://www.strava.com/settings/api" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Strava API Settings</a> and create a new application:</p>
                                    <ul class="list-disc pl-6 space-y-1 text-gray-700 dark:text-gray-300">
                                        <li><strong>Application Name:</strong> Your App Name</li>
                                        <li><strong>Category:</strong> Choose appropriate category</li>
                                        <li><strong>Website:</strong> https://yourdomain.com</li>
                                        <li><strong>Authorization Callback Domain:</strong> yourdomain.com</li>
                                    </ul>
                                    <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                        <p class="text-yellow-700 dark:text-yellow-300 text-sm">
                                            <strong>💡 Important:</strong> The callback domain must match your domain exactly (without http/https prefix)
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">3</div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold mb-2">Configure Environment Variables</h3>
                                    <p class="mb-3">Add these to your <code>.env</code> file with your Strava app credentials:</p>
                                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code>STRAVA_CLIENT_ID=your-client-id-from-strava
STRAVA_CLIENT_SECRET=your-client-secret-from-strava
STRAVA_REDIRECT_URI=https://yourdomain.com/strava/callback

# Optional: Configure where to redirect after successful connection
STRAVA_REDIRECT_AFTER_CONNECT=/dashboard</code></pre>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">4</div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold mb-2">Publish Configuration & Run Migrations</h3>
                                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code>php artisan vendor:publish --tag=strava-client-config
php artisan vendor:publish --tag=strava-client-migrations
php artisan migrate</code></pre>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">5</div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold mb-2">Add Trait to User Model</h3>
                                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code>use JordanPartridge\StravaClient\Concerns\HasStravaTokens;
use JordanPartridge\StravaClient\Contracts\HasStravaToken;

class User extends Authenticatable implements HasStravaToken
{
    use HasStravaTokens;

    // Your existing code...
}</code></pre>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">✓</div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold mb-2 text-green-700 dark:text-green-400">That's It! Start Using the API</h3>
                                    <p class="mb-3">Users can now connect their Strava accounts and you can fetch their data:</p>
                                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code>// In your Blade template - connect Strava button
&lt;a href="/strava/redirect" class="btn btn-primary"&gt;
    Connect Strava Account
&lt;/a&gt;

// In your controller - fetch activities (auto-refreshes tokens!)
use JordanPartridge\StravaClient\Facades\StravaClient;

$activities = StravaClient::activityForAthlete(1, 30);
$athlete = StravaClient::getAthlete();
$activity = StravaClient::getActivity(12345678);</code></pre>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <p class="text-blue-700 dark:text-blue-300 text-center">
                                <strong>🎉 Congratulations!</strong> You now have a fully functional Strava integration with automatic token refresh, zero-config OAuth, and bulletproof error handling.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-semibold">Basic Usage</h2>

                    <h3 class="text-xl font-semibold mt-6">Zero-Configuration Authentication</h3>
                    <p><strong>The package automatically handles the entire OAuth 2.0 flow!</strong> No routes, controllers, or callback handling needed.</p>

                    <h4 class="text-lg font-semibold mt-4">Step 1: Add the HasStravaTokens trait to your User model</h4>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>use JordanPartridge\StravaClient\Concerns\HasStravaTokens;
use JordanPartridge\StravaClient\Contracts\HasStravaToken;

class User extends Authenticatable implements HasStravaToken
{
    use HasStravaTokens;
    // ...
}</code></pre>

                    <h4 class="text-lg font-semibold mt-4">Step 2: Direct users to start authentication</h4>
                    <p>The package provides ready-to-use routes automatically:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>// In your blade template or controller
&lt;a href="/strava/redirect" class="btn btn-primary"&gt;
    Connect Strava Account
&lt;/a&gt;

// Or programmatically
return redirect('/strava/redirect');</code></pre>

                    <h4 class="text-lg font-semibold mt-4">Step 3: That's it! 🎉</h4>
                    <p>The package automatically:</p>
                    <ul class="list-disc pl-6 mt-2">
                        <li>Handles OAuth redirect to Strava</li>
                        <li>Processes the callback at <code>/strava/callback</code></li>
                        <li>Exchanges authorization code for tokens</li>
                        <li>Stores encrypted tokens in the database</li>
                        <li>Redirects users back to your configured success page</li>
                    </ul>

                    <p class="mt-4"><strong>Configure where to redirect after successful connection:</strong></p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>// In config/strava-client.php
'redirect_after_connect' => '/dashboard', // or wherever you want</code></pre>
                </div>

                <div class="mb-8">
                    <h3 class="text-xl font-semibold">Automatic Token Refresh - Set and Forget!</h3>
                    <p><strong>The package automatically refreshes expired tokens on every API call.</strong> You never need to worry about token expiration again!</p>

                    <h4 class="text-lg font-semibold mt-4">How Auto-Refresh Works:</h4>
                    <ol class="list-decimal pl-6 mt-2 space-y-2">
                        <li>You make any API call (activities, athlete data, etc.)</li>
                        <li>Package checks if token is expired or will expire soon</li>
                        <li>If expired, automatically refreshes token using refresh_token</li>
                        <li>Updates database with new access_token and expires_at</li>
                        <li>Retries the original API call with fresh token</li>
                        <li>Returns your data as if nothing happened!</li>
                    </ol>

                    <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 rounded-lg p-4 border border-blue-200 dark:border-blue-800 mt-4">
                        <p class="text-blue-700 dark:text-blue-300"><strong>💡 Pro Tip:</strong> The package will attempt up to 3 refresh attempts before giving up. If all attempts fail, it throws a <code>MaxAttemptsException</code> so you can handle the error gracefully.</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-xl font-semibold">Fetching Activities</h3>
                    <p>Once authenticated, simply make API calls. Token refresh happens automatically behind the scenes:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>use JordanPartridge\StravaClient\Facades\StravaClient;

// Set tokens from your stored user tokens
$token = auth()->user()->stravaToken;
StravaClient::setToken($token->access_token, $token->refresh_token);

// Get recent activities (page 1, 30 per page)
$activities = StravaClient::activityForAthlete(1, 30);

// Get more activities (page 2, 50 per page)
$moreActivities = StravaClient::activityForAthlete(2, 50);

// Get a specific activity by ID
$activity = StravaClient::getActivity(12345678);</code></pre>
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
                    <p>The package automatically handles token refreshing when tokens expire. Here's how it works:</p>

                    <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
                        <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">How Auto-Refresh Works:</h4>
                        <ol class="list-decimal list-inside text-blue-700 dark:text-blue-300 space-y-1">
                            <li>You make an API call with potentially expired tokens</li>
                            <li>If Strava returns HTTP 401 (token expired), the package detects this</li>
                            <li>Package automatically uses the refresh_token to get new tokens</li>
                            <li>New tokens are set in the client automatically</li>
                            <li>Your original API request is retried with fresh tokens</li>
                            <li>Data is returned as if nothing happened!</li>
                        </ol>
                    </div>

                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>// You don't need to check token expiry - the package handles it!
$token = auth()->user()->stravaToken;
StravaClient::setToken($token->access_token, $token->refresh_token);

// This call will auto-refresh tokens if needed
$activities = StravaClient::activityForAthlete(1, 30);

// Built-in retry protection (max 3 attempts)
// Throws MaxAttemptsException if refresh fails repeatedly</code></pre>
                </div>

                <div class="mb-8">
                    <h3 class="text-xl font-semibold">Error Handling</h3>
                    <p>The package provides comprehensive error handling for various API scenarios:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto">
<code>use JordanPartridge\StravaClient\Facades\StravaClient;
use JordanPartridge\StravaClient\Exceptions\Request\RateLimitExceededException;
use JordanPartridge\StravaClient\Exceptions\Request\ResourceNotFoundException;
use JordanPartridge\StravaClient\Exceptions\Authentication\MaxAttemptsException;

try {
    $activities = StravaClient::activityForAthlete(1, 30);
} catch (RateLimitExceededException $e) {
    // Handle rate limiting (429 status)
    logger('Rate limited by Strava API');
} catch (ResourceNotFoundException $e) {
    // Handle 404 errors
    logger('Resource not found');
} catch (MaxAttemptsException $e) {
    // Handle when token refresh fails repeatedly
    logger('Token refresh failed after max attempts');
}</code></pre>
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

                <!-- FAQ Section for SEO -->
                <div class="mb-12">
                    <h2 class="text-2xl font-semibold mb-4">Frequently Asked Questions</h2>

                    <div class="space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-2">How does Laravel Strava Client handle token refresh?</h3>
                            <p>The package automatically refreshes expired tokens when making API requests. You only need to implement the <code>HasStravaTokens</code> trait in your User model, and the package will handle refreshing the token when it expires.</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-2">Which Laravel versions are supported?</h3>
                            <p>Laravel Strava Client is compatible with Laravel 8, 9, 10, and 11. For older Laravel versions, you may need to use a previous version of the package.</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-2">How does the automatic token refresh work?</h3>
                            <p>When you make an API call and Strava returns HTTP 401 (token expired), the package automatically uses your stored refresh_token to get new access tokens, then retries your original request. This happens transparently with built-in retry protection (max 3 attempts).</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-2">Can I use this package with Laravel Sail or Docker?</h3>
                            <p>Yes, the package works seamlessly with Laravel Sail and Docker environments. There are no specific configuration changes needed for containerized environments.</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-2">Does this package support queued jobs for syncing activities?</h3>
                            <p>Yes, you can easily dispatch Laravel jobs to sync activities in the background. This is especially useful when setting up webhooks to receive real-time updates from Strava.</p>
                        </div>
                    </div>
                </div>

                <!-- User Testimonial -->
                <div class="mb-12 bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        <img src="/img/bike-joy.jpg" alt="User of Laravel Strava Client" class="w-24 h-24 rounded-full object-cover">
                        <div>
                            <div class="flex mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <blockquote class="italic mb-2">"This package saved me hours of development time. I was able to integrate Strava in my cycling app with just a few lines of code. The automatic token refresh handling is especially impressive - I never have to worry about expired tokens!"</blockquote>
                            <div class="font-semibold">Jordan P.</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Developer of Fat Bike Corps</div>
                        </div>
                    </div>
                </div>

                <!-- Integration Expertise Section -->
                <div class="mb-12 bg-gradient-to-r from-primary-50 to-blue-50 dark:from-primary-900 dark:to-blue-900 dark:bg-opacity-20 rounded-xl p-8 border border-primary-200 dark:border-primary-800">
                    <h2 class="text-2xl font-bold text-center mb-6">Built by a Laravel Integration Specialist</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </div>
                            <h3 class="font-semibold mb-2">Strava Integration</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Zero-config OAuth with automatic token refresh</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold mb-2">Gmail Integration</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Enterprise-grade email API integration</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold mb-2">GitHub Integration</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Repository sync and webhook handling</p>
                        </div>
                    </div>
                    <div class="text-center mt-8">
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            Jordan Partridge specializes in building <strong>production-ready Laravel integration packages</strong>
                            that follow enterprise standards and eliminate the complexity of API integrations.
                        </p>
                        <a href="/integrations" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            View All Integration Packages
                        </a>
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
                        See it in Action: Fat Bike Corps
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Add JavaScript for copy install command button -->
    <script>
    function copyInstallCommand() {
        const textToCopy = document.getElementById('copy-text').innerText;
        const tooltip = document.getElementById('copy-tooltip');

        navigator.clipboard.writeText(textToCopy).then(() => {
            // Show tooltip
            tooltip.classList.remove('hidden');

            // Hide tooltip after 2 seconds
            setTimeout(() => {
                tooltip.classList.add('hidden');
            }, 2000);
        });
    }
    </script>

    <!-- Add structured data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Laravel Strava Client",
        "applicationCategory": "DeveloperApplication",
        "operatingSystem": "Any",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "USD"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "5",
            "ratingCount": "1"
        },
        "author": {
            "@type": "Person",
            "name": "Jordan Partridge"
        }
    }
    </script>
</x-layouts.marketing>