<?php

/*
|--------------------------------------------------------------------------
| User Module Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Endpoints\ChangePasswordEndpoint;
use Modules\User\Http\Endpoints\ProfileIndexEndpoint;
use Modules\User\Http\Endpoints\ProfileUpdateEndpoint;
use Modules\User\Http\Endpoints\UpdateDiscountsEndpoint;
use Modules\User\Http\Endpoints\UserDetailStatisticsEndpoint;
use Modules\User\Http\Endpoints\UsersCreateEndpoint;
use Modules\User\Http\Endpoints\UsersEditEndpoint;
use Modules\User\Http\Endpoints\UsersIndexEndpoint;
use Modules\User\Http\Endpoints\UsersStatisticsEndpoint;
use Modules\User\Http\Endpoints\UsersStoreEndpoint;
use Modules\User\Http\Endpoints\UsersUpdateEndpoint;

Route::prefix('profile')->group(function () {
    Route::get('index')->name('profile.index')->uses(ProfileIndexEndpoint::class);
    Route::post('update')->name('profile.update')->uses(ProfileUpdateEndpoint::class);
    Route::post('change-password')->name('profile.password.change')->uses(ChangePasswordEndpoint::class);
    Route::post('update-discount')->name('profile.discount.update')->uses(UpdateDiscountsEndpoint::class);
});

Route::prefix('users')->group(function () {
    Route::get('/')->name('users.index')->uses(UsersIndexEndpoint::class);
    Route::get('create')->name('users.create')->uses(UsersCreateEndpoint::class);
    Route::get('stats')->name('users.statistics')->uses(UsersStatisticsEndpoint::class);

    Route::post('/')->name('users.store')->uses(UsersStoreEndpoint::class);

    Route::prefix("{user}")->group(function () {
        Route::post('stats/detail')->name('users.statistics.detail')->uses(UserDetailStatisticsEndpoint::class);
        Route::get('edit')->name('users.edit')->uses(UsersEditEndpoint::class);
        Route::put('/')->name('users.update')->uses(UsersUpdateEndpoint::class);
    });
});


