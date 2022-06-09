<?php

/*
|--------------------------------------------------------------------------
| Selection Module Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Endpoints\EpCreateSelection;
use Modules\Selection\Http\Endpoints\EpPumpStationCurves;
use Modules\Selection\Http\Endpoints\EpSelectionsDashboard;
use Modules\Selection\Http\Endpoints\EpDestroySelection;
use Modules\Selection\Http\Endpoints\EpExportSelectionAtOnce;
use Modules\Selection\Http\Endpoints\EpExportSelection;
use Modules\Selection\Http\Endpoints\EpRestoreSelection;
use Modules\Selection\Http\Endpoints\EpMakeSelection;
use Modules\Selection\Http\Endpoints\EpShowSelection;
use Modules\Selection\Http\Endpoints\EpUpdateSelection;
use Modules\Selection\Http\Middleware\DetermineSelection;

Route::prefix('projects/{project}/selections')
    ->middleware(['auth.project'])
    ->group(function () {
        Route::get('dashboard')->name('selections.dashboard')->uses(EpSelectionsDashboard::class);

        Route::prefix('{station_type}/{selection_type}')
            ->middleware(DetermineSelection::class)
            ->group(function () {
                Route::get('/')->name('selections.create')->uses(EpCreateSelection::class);
            });
    });

Route::prefix('selections')->as('selections.')->group(function () {
//    Route::prefix('{selection}')->middleware(['auth.project_of_selection'])->group(function () {
//        Route::middleware(['can:selections_show'])->group(function () {
//            Route::get('/')->name('show')->uses(EpShowSelection::class);
//            Route::put('/')->can('selections_edit')->name('update')->uses(EpUpdateSelection::class);
//        });
//
//        Route::get('restore')->can('selections_restore')->name('restore')->uses(EpRestoreSelection::class);
//        Route::post('export')->can('selections_export')->name('export')->uses(EpExportSelection::class);
//        Route::delete('/')->can('selections_delete')->name('destroy')->uses(EpDestroySelection::class);
//    });

    Route::middleware(DetermineSelection::class)->group(function () {
        Route::post('select')->name('select')->uses(EpMakeSelection::class);
    });

//    Route::post('export/at-once')->can('selections_export')->name('export.at_once')->uses(EpExportSelectionAtOnce::class);
});

Route::prefix('pump-stations')->as('pump_stations.')->group(function() {
    Route::post('curves')->name('curves')->uses(EpPumpStationCurves::class);
});
