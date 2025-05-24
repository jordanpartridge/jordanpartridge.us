<?php

use App\Http\Controllers\TerminalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Terminal API Routes
|--------------------------------------------------------------------------
|
| These routes handle the browser terminal functionality, allowing the
| frontend to execute real artisan commands through API calls.
|
*/

Route::prefix('api/terminal')->group(function () {
    Route::post('/execute', [TerminalController::class, 'execute'])->name('terminal.execute');
});