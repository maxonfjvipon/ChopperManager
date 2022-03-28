<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\PumpsController;
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

// DEFAULT
Route::redirect('/', '/projects');

// GUEST
Route::middleware('guest')->group(function () {
    Route::get('login')->name('login')->uses([LoginController::class, 'showLoginForm']);
    Route::post('login')->name('login.attempt')->uses([LoginController::class, 'login']);
});

// AUTH
Route::middleware('auth')->group(function() {
    Route::resource('projects', ProjectsController::class);
});
