@php
    use function Laravel\Folio\name;
    use function Laravel\Folio\{middleware};

    name('profile.index');
    middleware(['web', 'auth']);
@endphp

@volt
<?php
$user = auth()->user();
?>

<div>
    <x-app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h1 class="text-2xl font-semibold mb-6">Profile</h1>
                    @auth
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium">Profile Information</h3>
                                <p class="text-gray-600 dark:text-gray-400">Update your account's profile information and email address.</p>
                            </div>

                            <div class="mt-6">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $user->name }}</dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $user->email }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </x-app-layout>
</div>
@endvolt