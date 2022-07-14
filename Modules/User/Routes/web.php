<?php

/*
|--------------------------------------------------------------------------
| User Module Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Endpoints\EpCreateOrEditUser;
use Modules\User\Http\Endpoints\EpShowUser;
use Modules\User\Http\Endpoints\EpStoreUser;
use Modules\User\Http\Endpoints\EpUpdateUser;
use Modules\User\Http\Endpoints\EpUsers;
use Modules\User\Http\Endpoints\Profile\EpChangePassword;
use Modules\User\Http\Endpoints\Profile\EpProfile;
use Modules\User\Http\Endpoints\Profile\EpUpdateProfile;

// PROFILE
Route::prefix('profile')->as('profile.')->group(function () {
    Route::get('index')->name('index')->uses(EpProfile::class);
    Route::post('update')->name('update')->uses(EpUpdateProfile::class);
    Route::post('change-password')->name('password.change')->uses(EpChangePassword::class);
//    Route::post('update-discount')->name('discount.update')->uses(EpUpdateDiscount::class);
});

// USERS
Route::prefix('users')->as('users.')->middleware('admin')->group(function () {
    Route::get('create')->name('create')->uses(EpCreateOrEditUser::class);
    Route::post('/')->name('store')->uses(EpStoreUser::class);

    Route::get('/')->name('index')->uses(EpUsers::class);
//        Route::get('stats')->name('statistics')->uses(EpUsersStatistics::class);

    Route::prefix('{user}')->group(function () {
        Route::get('edit')->name('edit')->uses(EpCreateOrEditUser::class);
        Route::get('show')->name('show')->uses(EpShowUser::class);
        Route::post('/')->name('update')->uses(EpUpdateUser::class);

//            Route::post('stats/detail')->name('statistics.detail')->uses(EpDetailUserStatistics::class);
    });
});
