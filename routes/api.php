<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShareCountController;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Social sharing endpoints
Route::get('share-count', [ShareCountController::class, 'getCount'])->middleware('throttle:api');
Route::post('track-share', [ShareCountController::class, 'trackShare'])->middleware('throttle:api');
