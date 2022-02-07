<?php

/*
|--------------------------------------------------------------------------
| User Module Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\User\Endpoints\UsersIndexEndpoint;
use Modules\User\Http\Controllers\ProfileController;
use Modules\User\Http\Controllers\UsersController;

Route::prefix('profile')->group(function () {
    Route::get('index')->name('profile.index')->uses([ProfileController::class, 'index']);
    Route::post('update')->name('profile.update')->uses([ProfileController::class, 'update']);
    Route::post('change-password')->name('profile.password.change')->uses([ProfileController::class, 'changePassword']);
    Route::post('update-discount')->name('profile.discount.update')->uses([ProfileController::class, 'updateDiscount']);
});

Route::prefix('users')->group(function () {
    Route::get('/')->name('users.index')->uses(UsersIndexEndpoint::class);
    Route::get('create')->name('users.create')->uses([UsersController::class, 'create']);
    Route::get('{user}/edit')->name('users.edit')->uses([UsersController::class, 'edit']);
    Route::post('/')->name('users.store')->uses([UsersController::class, 'store']);
    Route::put('{user}')->name('users.update')->uses([UsersController::class, 'update']);
});
//Route::resource('users', UsersController::class)->except(['show']);


