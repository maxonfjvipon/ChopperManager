<?php

/*
|--------------------------------------------------------------------------
| User Module Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Endpoints\Profile\EpChangePassword;
use Modules\User\Http\Endpoints\Profile\EpProfile;
use Modules\User\Http\Endpoints\Profile\EpUpdateProfile;
use Modules\User\Http\Endpoints\Profile\EpUpdateDiscount;
use Modules\User\Http\Endpoints\EpDetailUserStatistics;
use Modules\User\Http\Endpoints\EpCreateUser;
use Modules\User\Http\Endpoints\EpEditUser;
use Modules\User\Http\Endpoints\EqUsers;
use Modules\User\Http\Endpoints\EpUsersStatistics;
use Modules\User\Http\Endpoints\EpStoreUser;
use Modules\User\Http\Endpoints\EpUpdateUser;

// PROFILE
Route::prefix('profile')->as('profile.')->group(function () {
    Route::get('index')->name('index')->uses(EpProfile::class);
    Route::post('update')->name('update')->uses(EpUpdateProfile::class);
    Route::post('change-password')->name('password.change')->uses(EpChangePassword::class);
    Route::post('update-discount')->name('discount.update')->uses(EpUpdateDiscount::class);
});

// USERS
Route::prefix('users')->as('users.')->group(function () {
    Route::middleware(['can:user_create'])->group(function () {
        Route::get('create')->name('create')->uses(EpCreateUser::class);
        Route::post('/')->name('store')->uses(EpStoreUser::class);
    });

    Route::middleware(['can:user_access'])->group(function () {
        Route::get('/')->name('index')->uses(EqUsers::class);
        Route::get('stats')->can('user_statistics')->name('statistics')->uses(EpUsersStatistics::class);
    });

    Route::prefix("{user}")->group(function () {
        Route::middleware(['can:user_edit'])->group(function () {
            Route::get('edit')->name('edit')->uses(EpEditUser::class);
            Route::put('/')->name('update')->uses(EpUpdateUser::class);
        });

        Route::post('stats/detail')->can('user_statistics')->name('statistics.detail')->uses(EpDetailUserStatistics::class);
    });
});


