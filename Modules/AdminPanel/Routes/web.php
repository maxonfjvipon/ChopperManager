<?php

/*
|--------------------------------------------------------------------------
| Admin Panel Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\AdminPanel\Http\Controllers\Auth\LoginController;
use Modules\AdminPanel\Http\Controllers\TenantsController;

// LOGIN
Route::middleware('guest.admin')->group(function () {
    Route::get('login')->name('login')->uses([LoginController::class, 'showLoginForm']);
    Route::post('login')->name('login.attempt')->uses([LoginController::class, 'login']);
});

// LOGOUT
Route::post('logout')->name('logout')->uses([LoginController::class, 'logout']);

// AUTHED
Route::middleware('auth.admin')->group(function () {
    // TENANTS
    Route::redirect('/', '/tenants')->name('index');
    Route::resource('tenants', TenantsController::class)->except(['edit', 'destroy']);
});
