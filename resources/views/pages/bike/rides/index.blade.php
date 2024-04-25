<?php

use function Laravel\Folio\{name};
use function Livewire\Volt\{mount};

name('bike.rides.index');
mount(function () {
    return [
        'rides' => \App\Models\Ride::query()->latest()->paginate(10),
    ];
});
