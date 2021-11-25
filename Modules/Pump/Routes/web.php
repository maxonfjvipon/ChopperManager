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
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Http\Controllers\PumpsController;

$seriesController = Tenant::current()->getControllerClass('PumpSeriesController');
$brandsController = Tenant::current()->getControllerClass('PumpBrandsController');
$pumpsController = Tenant::current()->getControllerClass('PumpsController');

// PUMP BRANDS
Route::get('pump_brands/{brand}/restore')->name('pump_brands.restore')->uses([$brandsController, 'restore']);
Route::resource('pump_brands', $brandsController)->except(['index', 'show']);

// PUMP SERIES
Route::prefix('pump_series')->group(function () use ($seriesController) {
    Route::get('{series}/restore')->name('pump_series.restore')->uses([$seriesController, 'restore']);
    Route::prefix('import')->group(function () use ($seriesController) {
        Route::post('/')->name('pump_series.import')->uses([$seriesController, 'import']);
        Route::post('media')->name('pump_series.import.media')->uses([$seriesController, 'importMedia']);
    });
});
Route::resource('pump_series', $seriesController)->except(['show']);

// PUMPS
Route::prefix('pumps/import')->group(function () {
    Route::post('/')->name('pumps.import')->uses([PumpsController::class, 'import']);
    Route::post('price_list')->name('pumps.import.price_lists')->uses([PumpsController::class, 'importPriceLists']);
    Route::post('media')->name('pumps.import.media')->uses([PumpsController::class, 'importMedia']);
});
Route::resource('pumps', $pumpsController)->except(['edit', 'create']);
