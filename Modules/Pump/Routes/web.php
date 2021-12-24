<?php

/*
|--------------------------------------------------------------------------
| Pump Module Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Pump\Http\Controllers\PumpBrandsController;
use Modules\Pump\Http\Controllers\PumpsController;
use Modules\Pump\Http\Controllers\PumpSeriesController;


// PUMP BRANDS
Route::get('pump_brands/{brand}/restore')->name('pump_brands.restore')->uses([PumpBrandsController::class, 'restore']);
Route::resource('pump_brands', PumpBrandsController::class)->except(['index', 'show']);

// PUMP SERIES
Route::prefix('pump_series')->group(function () {
    Route::get('{series}/restore')->name('pump_series.restore')->uses([PumpSeriesController::class, 'restore']);
    Route::prefix('import')->group(function () {
        Route::post('/')->name('pump_series.import')->uses([PumpSeriesController::class, 'import']);
        Route::post('media')->name('pump_series.import.media')->uses([PumpSeriesController::class, 'importMedia']);
    });
});
Route::resource('pump_series', PumpSeriesController::class)->except(['show']);

// PUMPS
Route::prefix('pumps/import')->group(function () {
    Route::post('/')->name('pumps.import')->uses([PumpsController::class, 'import']);
    Route::post('price_list')->name('pumps.import.price_lists')->uses([PumpsController::class, 'importPriceLists']);
    Route::post('media')->name('pumps.import.media')->uses([PumpsController::class, 'importMedia']);
});
Route::prefix('pumps')->group(function () {
    Route::post('load')->name('pumps.load')->uses([PumpsController::class, 'load']);
    Route::prefix('{pump}')->group(function () {
        Route::post('/')->name('pumps.show')->uses([PumpsController::class, 'show']);
        Route::post('add-to-projects')->name('pumps.add_to_projects')->uses([PumpsController::class, 'addToProjects']);
    });
});
Route::resource('pumps', PumpsController::class)->except(['edit', 'create', 'show']);
