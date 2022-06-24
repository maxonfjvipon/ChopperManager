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
use Modules\Auth\Http\Endpoints\EpAwait;
use Modules\Auth\Http\Endpoints\EpLoginAttempt;
use Modules\Auth\Http\Endpoints\EpLogin;
use Modules\Auth\Http\Endpoints\EpLogout;
use Modules\Auth\Http\Endpoints\EpRegisterAttempt;
use Modules\Auth\Http\Endpoints\EpRegister;

Route::middleware('guest')->group(function () {
    // LOGIN
    Route::get('login')->name('login')->uses(EpLogin::class);
    Route::post('login')->name('login.attempt')->uses(EpLoginAttempt::class);

    // REGISTER
//    Route::get('register')->name('register')->uses(EpRegister::class);
//    Route::post('register')->name('register.attempt')->uses(EpRegisterAttempt::class);
});

// AUTHORIZED
Route::middleware('auth')->group(function () {
    // LOGOUT
    Route::post('logout')->name('logout')->uses(EpLogout::class);

    // AWAIT
    Route::get('await')->name('await')->uses(EpAwait::class);
});
