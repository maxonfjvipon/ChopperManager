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
use Modules\User\Endpoints\UsersStatisticsEndpoint;
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
    Route::post('/')->name('users.store')->uses([UsersController::class, 'store']);

    Route::prefix("{user}")->group(function() {
        Route::get('edit')->name('users.edit')->uses([UsersController::class, 'edit']);
        Route::put('/')->name('users.update')->uses([UsersController::class, 'update']);
        Route::post('statistics')->name('users.statistics')->uses(UsersStatisticsEndpoint::class);
    });
});


