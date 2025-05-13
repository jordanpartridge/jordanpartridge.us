@extends('components.layout')

@section('content')
<div class="max-w-2xl mx-auto py-16 px-4">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-bold text-gray-900">Gmail API Integration Test</h1>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Test your connection to the Gmail API
            </p>
        </div>

        @if (session('error'))
            <div class="rounded-md bg-red-50 p-4 my-4 mx-6">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Error</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4 my-4 mx-6">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Success</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Authentication Status</h2>
                    <div class="mt-2">
                        @if (session('gmail_access_token'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-green-100 text-green-800">
                                Authenticated
                            </span>
                            <p class="mt-2 text-sm text-gray-500">Token expires: {{ session('gmail_token_expires_at') }}</p>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-red-100 text-red-800">
                                Not Authenticated
                            </span>
                            <p class="mt-2 text-sm text-gray-500">You need to authenticate with Gmail first.</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    <div>
                        <a href="{{ route('gmail.auth') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Authenticate with Gmail
                        </a>
                    </div>

                    @if (session('gmail_access_token'))
                        <div>
                            <a href="{{ route('gmail.messages') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                List Recent Messages
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('gmail.labels') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                List Labels
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Setup Instructions</h3>
            <div class="mt-2 text-sm text-gray-500">
                <ol class="list-decimal pl-4 space-y-2">
                    <li>Create a new project in the <a href="https://console.developers.google.com/" target="_blank" class="text-indigo-600 hover:text-indigo-900">Google Developer Console</a></li>
                    <li>Enable the Gmail API</li>
                    <li>Configure the OAuth consent screen</li>
                    <li>Create OAuth credentials (Web application type)</li>
                    <li>Add the redirect URI: <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ config('gmail-client.redirect_uri') }}</code></li>
                    <li>Add the Client ID and Client Secret to your <code>.env</code> file</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection