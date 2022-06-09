<?php

use Illuminate\Support\Facades\Route;
use Modules\PumpSeries\Http\Endpoints\EpCreatePumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpDestroyPumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpEditPumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpRestorePumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpStorePumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpUpdatePumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpCreatePumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpDestroyPumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpEditPumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpPumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpRestorePumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpStorePumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpUpdatePumpSeries;

/*
|--------------------------------------------------------------------------
| Pump series Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware('admin')->group(function () {

    // PUMP SERIES
    Route::prefix('pump-series')->as('pump_series.')->group(function () {
        Route::get('/')->name('index')->uses(EpPumpSeries::class);
        Route::get('create')->name('create')->uses(EpCreatePumpSeries::class);

        Route::post('/')->name('store')->uses(EpStorePumpSeries::class);

        Route::prefix('{pump_series}')->group(function () {
            Route::get('restore')->name('restore')->uses(EpRestorePumpSeries::class);
            Route::get('edit')->name('edit')->uses(EpEditPumpSeries::class);

            Route::put('/')->name('update')->uses(EpUpdatePumpSeries::class);

            Route::delete('/')->can('series_delete')->name('destroy')->uses(EpDestroyPumpSeries::class);
        });
    });

    // PUMP BRANDS
    Route::prefix('pump-brands')->as('pump_brands.')->group(function () {
        Route::get('create')->name('create')->uses(EpCreatePumpBrand::class);

        Route::post('/')->name('store')->uses(EpStorePumpBrand::class);

        Route::prefix('{pump_brand}')->group(function () {
            Route::get('restore')->name('restore')->uses(EpRestorePumpBrand::class);
            Route::get('edit')->name('edit')->uses(EpEditPumpBrand::class);

            Route::put('/')->name('update')->uses(EpUpdatePumpBrand::class);

            Route::delete('/')->name('destroy')->uses(EpDestroyPumpBrand::class);
        });
    });
});
