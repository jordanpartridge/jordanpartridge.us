<?php

use Illuminate\Support\Facades\Route;

Route::get('customer-test', function () {
    return 'Customer Role Test: Success!';
})->middleware('role:Customer');
