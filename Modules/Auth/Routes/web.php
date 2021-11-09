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
use Modules\AdminPanel\Entities\Tenant;
use Modules\Auth\Http\Controllers\EmailVerificationController;

$loginControllerClass = Tenant::current()->getControllerClass('LoginController');
$registerControllerClass = Tenant::current()->getControllerClass('RegisterController');

Route::middleware('guest.module')->group(function () use ($loginControllerClass, $registerControllerClass) {
    // LOGIN
    Route::get('login')->name('login')->uses([$loginControllerClass, 'showLoginForm']);
    Route::post('login')->name('login.attempt')->uses([$loginControllerClass, 'login']);

    // REGISTER
    Route::middleware('has_registration')->group(function () use ($registerControllerClass) {
        Route::get('register')->name('register')->uses([$registerControllerClass, 'showRegisterForm']);
        Route::post('register')->name('register.attempt')->uses([$registerControllerClass, 'register']);
    });
});

// LOGOUT
Route::post('logout')->name('logout')->uses([$loginControllerClass, 'logout']);

// AUTHORIZED
Route::middleware('auth.module')->group(function () {
    // EMAIL VERIFICATION
    Route::get('/email/verify')->name('verification.notice')->uses([EmailVerificationController::class, 'notice']);
    Route::get('/email/verify/{id}/{hash}')->name('verification.verify')->middleware('signed')->uses([EmailVerificationController::class, 'verify']);
    Route::post('/email/verification-notification')->name('verification.send')->middleware('throttle:6,1')->uses([EmailVerificationController::class, 'resendVerification']);
});
