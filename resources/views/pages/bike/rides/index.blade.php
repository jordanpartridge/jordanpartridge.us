<?php

use App\Models\Ride;

use function Laravel\Folio\{name};
use function Livewire\Volt\{mount};

name('bike.rides.index');
mount(function () {
    return [
        'rides' => Ride::query()->latest()->paginate(10),
    ];
});
