<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\StravaController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('home', '/')->name('home');

Route::post('slack', function () {
    return request()->input('challenge');
})->name('slack.hook')->withoutMiddleware(VerifyCsrfToken::class);

Route::middleware('auth')->group(callback: function () {
    Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('logout', LogoutController::class)
        ->name('logout');

    Route::get('/strava/redirect', [StravaController::class, 'redirect'])->name('strava.redirect');
    Route::get('/strava/callback', [StravaController::class, 'callback'])->name('strava.callback');

});
