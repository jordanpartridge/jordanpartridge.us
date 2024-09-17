<div>
    @env('local')
        @if (Auth::check() === false)
        <div class="space-y-2">
            <x-login-link class="p-3 text-gray-700 dark:text-gray-300" :email="$email" label="Login"/>
        </div>
        @endif
    @endenv
</div>
