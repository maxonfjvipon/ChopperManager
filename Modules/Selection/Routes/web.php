<?php

/*
|--------------------------------------------------------------------------
| Selection Module Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\Project\Http\Middleware\AuthorizeProject;
use Modules\Selection\Http\Endpoints\EpCreateSelection;
use Modules\Selection\Http\Endpoints\EpDestroySelection;
use Modules\Selection\Http\Endpoints\EpMakeSelection;
use Modules\Selection\Http\Endpoints\EpPumpStationCurves;
use Modules\Selection\Http\Endpoints\EpRestoreSelection;
use Modules\Selection\Http\Endpoints\EpSelectionsDashboard;
use Modules\Selection\Http\Endpoints\EpShowSelection;
use Modules\Selection\Http\Endpoints\EpStoreSelection;
use Modules\Selection\Http\Endpoints\EpUpdatePumpStationCost;
use Modules\Selection\Http\Endpoints\EpUpdateSelection;
use Modules\Selection\Http\Middleware\AuthorizeSelection;
use Modules\Selection\Http\Middleware\DetermineSelection;

Route::prefix('projects/{project}/selections')
    ->middleware(AuthorizeProject::class)
    ->as('selections.')
    ->group(function () {
        Route::get('dashboard')->name('dashboard')->uses(EpSelectionsDashboard::class);
        Route::post('store')->name('store')->uses(EpStoreSelection::class);
        Route::get('create')->name('create')->uses(EpCreateSelection::class);
    });

Route::prefix('selections')->as('selections.')->group(function () {
    Route::prefix('{selection}')->middleware(AuthorizeSelection::class)->group(function () {
        Route::get('/')->name('show')->uses(EpShowSelection::class);
        Route::put('/')->name('update')->uses(EpUpdateSelection::class);
        Route::get('restore')->name('restore')->uses(EpRestoreSelection::class);
        Route::delete('/')->name('destroy')->uses(EpDestroySelection::class);
    });

    Route::middleware(DetermineSelection::class)->group(function () {
        Route::post('select')->name('select')->uses(EpMakeSelection::class);
    });

//    Route::post('export/at-once')->can('selections_export')->name('export.at_once')->uses(EpExportSelectionAtOnce::class);
});

Route::prefix('pump-stations')->as('pump_stations.')->group(function () {
    Route::post('curves')->name('curves')->uses(EpPumpStationCurves::class);
    Route::post('markup-by-template')->name('markup_by_template');

    Route::prefix('{pump_station}')->group(function () {
        Route::post('update-cost')->name('update_cost')->uses(EpUpdatePumpStationCost::class);
    });
});
