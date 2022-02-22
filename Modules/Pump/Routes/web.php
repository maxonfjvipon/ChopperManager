<?php

/*
|--------------------------------------------------------------------------
| Pump Module Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\Pump\Http\Endpoints\Pump\LoadPumpsEndpoint;
use Modules\Pump\Http\Endpoints\Pump\PumpsAddToProjectsEndpoint;
use Modules\Pump\Http\Endpoints\Pump\PumpsImportEndpoint;
use Modules\Pump\Http\Endpoints\Pump\PumpsImportMediaEndpoint;
use Modules\Pump\Http\Endpoints\Pump\PumpsImportPriceListsEndpoint;
use Modules\Pump\Http\Endpoints\Pump\PumpsIndexEndpoint;
use Modules\Pump\Http\Endpoints\Pump\PumpsShowEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsCreateEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsDestroyEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsEditEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsRestoreEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsStoreEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsUpdateEndpoint;
use Modules\Pump\Http\Endpoints\PumpSeries\PumpSeriesCreateEndpoint;
use Modules\Pump\Http\Endpoints\PumpSeries\PumpSeriesDestroyEndpoint;
use Modules\Pump\Http\Endpoints\PumpSeries\PumpSeriesEditEndpoint;
use Modules\Pump\Http\Endpoints\PumpSeries\PumpSeriesImportEndpoint;
use Modules\Pump\Http\Endpoints\PumpSeries\PumpSeriesImportMediaEndpoint;
use Modules\Pump\Http\Endpoints\PumpSeries\PumpSeriesIndexEndpoint;
use Modules\Pump\Http\Endpoints\PumpSeries\PumpSeriesRestoreEndpoint;
use Modules\Pump\Http\Endpoints\PumpSeries\PumpSeriesStoreEndpoint;
use Modules\Pump\Http\Endpoints\PumpSeries\PumpSeriesUpdateEndpoint;

// PUMP BRANDS
Route::prefix('pump_brands')->group(function () {
    Route::post('/')->name('pump_brands.store')->uses(PumpBrandsStoreEndpoint::class);
    Route::get('create')->name('pump_brands.create')->uses(PumpBrandsCreateEndpoint::class);
    Route::prefix('{pump_brand}')->group(function () {
        Route::get('restore')->name('pump_brands.restore')->uses(PumpBrandsRestoreEndpoint::class);
        Route::get('edit')->name('pump_brands.edit')->uses(PumpBrandsEditEndpoint::class);
        Route::put('/')->name('pump_brands.update')->uses(PumpBrandsUpdateEndpoint::class);
        Route::delete('/')->name('pump_brands.destroy')->uses(PumpBrandsDestroyEndpoint::class);
    });
});

// PUMP SERIES
Route::prefix('pump_series')->group(function () {
    Route::get('/')->name('pump_series.index')->uses(PumpSeriesIndexEndpoint::class);
    Route::get('create')->name('pump_series.create')->uses(PumpSeriesCreateEndpoint::class);
    Route::post('/')->name('pump_series.store')->uses(PumpSeriesStoreEndpoint::class);

    Route::prefix('import')->group(function () {
        Route::post('/')->name('pump_series.import')->uses(PumpSeriesImportEndpoint::class);
        Route::post('media')->name('pump_series.import.media')->uses(PumpSeriesImportMediaEndpoint::class);
    });

    Route::prefix('{pump_series}')->group(function() {
        Route::get('restore')->name('pump_series.restore')->uses(PumpSeriesRestoreEndpoint::class);
        Route::get('edit')->name('pump_series.edit')->uses(PumpSeriesEditEndpoint::class);
        Route::put('/')->name('pump_series.update')->uses(PumpSeriesUpdateEndpoint::class);
        Route::delete('/')->name('pump_series.destroy')->uses(PumpSeriesDestroyEndpoint::class);
    });
});

// PUMPS
Route::prefix('pumps')->group(function () {
    Route::prefix('import')->group(function () {
        Route::post('/')->name('pumps.import')->uses(PumpsImportEndpoint::class);
        Route::post('price_list')->name('pumps.import.price_lists')->uses(PumpsImportPriceListsEndpoint::class);
        Route::post('media')->name('pumps.import.media')->uses(PumpsImportMediaEndpoint::class);
    });

    Route::get('/')->name('pumps.index')->uses(PumpsIndexEndpoint::class);
    Route::post('load')->name('pumps.load')->uses(LoadPumpsEndpoint::class);
    Route::prefix('{pump}')->group(function () {
        Route::post('/')->name('pumps.show')->uses(PumpsShowEndpoint::class);
        Route::post('add-to-projects')->name('pumps.add_to_projects')->uses(PumpsAddToProjectsEndpoint::class);
    });
});
