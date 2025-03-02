<x-filament-panels::page>
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md">
            <form action="{{ route('filament.admin.auth.login') }}" method="post" class="space-y-8">
                @csrf
                <div class="space-y-2">
                    <x-filament::input.wrapper
                        id="email"
                        label="Email"
                        required
                    >
                        <x-filament::input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Email"
                            required
                            autocomplete="email"
                            autofocus
                        />
                    </x-filament::input.wrapper>

                    <x-filament::input.wrapper
                        id="password"
                        label="Password"
                        required
                    >
                        <x-filament::input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                        />
                    </x-filament::input.wrapper>
                </div>

                <x-filament::button type="submit" class="w-full">
                    Login
                </x-filament::button>
            </form>
        </div>
    </div>
</x-filament-panels::page>
