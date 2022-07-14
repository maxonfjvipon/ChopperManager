<?php

/*
|--------------------------------------------------------------------------
| Pump Module Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\Pump\Http\Endpoints\EpImportPumps;
use Modules\Pump\Http\Endpoints\EpLoadPumps;
use Modules\Pump\Http\Endpoints\EpPumps;
use Modules\Pump\Http\Endpoints\EpShowPump;

// PUMPS
Route::prefix('pumps')->as('pumps.')->middleware('admin')->group(function () {
    Route::get('/')->name('index')->uses(EpPumps::class);
    Route::post('import')->name('import')->uses(EpImportPumps::class);
    Route::post('load')->name('load')->uses(EpLoadPumps::class);
    Route::post('{pump}')->name('show')->uses(EpShowPump::class);
});
