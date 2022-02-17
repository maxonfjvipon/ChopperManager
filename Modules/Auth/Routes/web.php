<?php

/*
|--------------------------------------------------------------------------
| Auth module Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Endpoints\LoginAttemptEndpoint;
use Modules\Auth\Http\Endpoints\LoginEndpoint;
use Modules\Auth\Http\Endpoints\LogoutEndpoint;
use Modules\Auth\Http\Endpoints\RegisterAttemptEndpoint;
use Modules\Auth\Http\Endpoints\RegisterEndpoint;
use Modules\Auth\Http\Controllers\EmailVerificationController;

Route::middleware('guest.module')->group(function () {
    // LOGIN
    Route::get('login')->name('login')->uses(LoginEndpoint::class);
    Route::post('login')->name('login.attempt')->uses(LoginAttemptEndpoint::class);

    // REGISTER
    Route::middleware('has_registration')->group(function () {
        Route::get('register')->name('register')->uses(RegisterEndpoint::class);
        Route::post('register')->name('register.attempt')->uses(RegisterAttemptEndpoint::class);
    });
});


// LOGOUT
Route::post('logout')->name('logout')->uses(LogoutEndpoint::class);

// AUTHORIZED
Route::middleware('auth.module')->group(function () {
    // EMAIL VERIFICATION
    Route::get('/email/verify')->name('verification.notice')->uses([EmailVerificationController::class, 'notice']);
    Route::get('/email/verify/{id}/{hash}')->name('verification.verify')->middleware('signed')->uses([EmailVerificationController::class, 'verify']);
    Route::post('/email/verification-notification')->name('verification.send')->middleware('throttle:6,1')->uses([EmailVerificationController::class, 'resendVerification']);
});
