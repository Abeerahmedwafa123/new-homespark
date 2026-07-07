<?php

use App\Http\Controllers\SimliController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| The Simli session endpoint. When merging into the LMS, copy just this
| route line into the LMS's routes/api.php.
|
| NOTE: On Laravel 11/12 a fresh app has no routes/api.php until you run
| `php artisan install:api`. Do that first, then add this line.
|
*/

Route::post('/simli/session', [SimliController::class, 'session'])
    ->middleware('throttle:30,1'); // -> POST /api/simli/session
