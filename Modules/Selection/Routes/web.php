<?php

/*
|--------------------------------------------------------------------------
| Selection Module Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\Selection\Endpoints\SelectionsCreateEndpoint;
use Modules\Selection\Endpoints\SelectionsCurvesEndpoint;
use Modules\Selection\Endpoints\SelectionsDashboardEndpoint;
use Modules\Selection\Endpoints\SelectionsDestroyEndpoint;
use Modules\Selection\Endpoints\SelectionsExportAtOnceEndpoint;
use Modules\Selection\Endpoints\SelectionsExportEndpoint;
use Modules\Selection\Endpoints\SelectionsRestoreEndpoint;
use Modules\Selection\Endpoints\SelectionsSelectEndpoint;
use Modules\Selection\Endpoints\SelectionsShowEndpoint;
use Modules\Selection\Endpoints\SelectionsStoreEndpoint;
use Modules\Selection\Endpoints\SelectionsUpdateEndpoint;

Route::prefix('projects/{project}')->group(function () {
    Route::prefix('selections')->group(function () {
        Route::get('dashboard')->name('selections.dashboard')->uses(SelectionsDashboardEndpoint::class);
        Route::get('create')->name('projects.selections.create')->uses(SelectionsCreateEndpoint::class);

        Route::post('/')->name('projects.selections.store')->uses(SelectionsStoreEndpoint::class);
    });
});

Route::prefix('selections')->group(function() {
    Route::prefix('{selection}')->group(function () {
        Route::get('/')->name('selections.show')->uses(SelectionsShowEndpoint::class);
        Route::get('restore')->name('selections.restore')->uses(SelectionsRestoreEndpoint::class);

        Route::post('export')->name('selections.export')->uses(SelectionsExportEndpoint::class);
        Route::put('/')->name('selections.update')->uses(SelectionsUpdateEndpoint::class);
        Route::delete('/')->name('selections.destroy')->uses(SelectionsDestroyEndpoint::class);
    });
    Route::post('curves')->name('selections.curves')->uses(SelectionsCurvesEndpoint::class);
    Route::post('export/at-once')->name('selections.export.at_once')->uses(SelectionsExportAtOnceEndpoint::class);
    Route::post('select')->name('selections.select')->uses(SelectionsSelectEndpoint::class);
});
