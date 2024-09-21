<a class="navbar-item {{ url($attributes->get('href')) == url()->current() ? 'is-active' : '' }}" {{ $attributes->except('wire:navigate') }} wire:navigate>{{ $slot }}</a>
