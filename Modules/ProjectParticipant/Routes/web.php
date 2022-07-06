<?php

/*
|--------------------------------------------------------------------------
| Project participants Web Routes
|--------------------------------------------------------------------------
*/


use Modules\ProjectParticipant\Http\Endpoints\EpCreateOrEditDealer;
use Modules\ProjectParticipant\Http\Endpoints\EpDealers;
use Modules\ProjectParticipant\Http\Endpoints\EpShowDealer;
use Modules\ProjectParticipant\Http\Endpoints\EpStoreDealer;
use Modules\ProjectParticipant\Http\Endpoints\EpUpdateDealer;

Route::middleware(['admin'])->group(function () {

    Route::prefix('contractors')->as('contractors.')->group(function () {
    });

    Route::prefix('dealers')->as('dealers.')->group(function () {
        Route::get('/')->name('index')->uses(EpDealers::class);
        Route::get('create')->name('create')->uses(EpCreateOrEditDealer::class);
        Route::post('store')->name('store')->uses(EpStoreDealer::class);

        Route::prefix('{dealer}')->group(function () {
            Route::get('edit')->name('edit')->uses(EpCreateOrEditDealer::class);
            Route::get('show')->name('show')->uses(EpShowDealer::class);
            Route::post('update')->name('update')->uses(EpUpdateDealer::class);
            Route::delete('destroy')->name('destroy');
        });
    });
});


