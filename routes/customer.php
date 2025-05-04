<?php

use Illuminate\Support\Facades\Route;

// Route that requires the Customer role
Route::middleware(['auth', 'role:Customer'])->group(function () {
    Route::get('customer-dashboard', function () {
        return 'Welcome to the Customer Dashboard. This content is only visible to users with the Customer role.';
    })->name('customer.dashboard');
});
