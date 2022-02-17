<?php

/*
|--------------------------------------------------------------------------
| Core Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Endpoints\ProjectsCloneEndpoint;
use Modules\Core\Http\Endpoints\ProjectsCreateEndpoint;
use Modules\Core\Http\Endpoints\ProjectsDestroyEndpoint;
use Modules\Core\Http\Endpoints\ProjectsEditEndpoint;
use Modules\Core\Http\Endpoints\ProjectsExportEndpoint;
use Modules\Core\Http\Endpoints\ProjectsIndexEndpoint;
use Modules\Core\Http\Endpoints\ProjectsRestoreEndpoint;
use Modules\Core\Http\Endpoints\ProjectsShowEndpoint;
use Modules\Core\Http\Endpoints\ProjectsStatisticsEndpoint;
use Modules\Core\Http\Endpoints\ProjectsStoreEndpoint;
use Modules\Core\Http\Endpoints\ProjectsUpdateEndpoint;

Route::redirect('/', app()->getLocale() . '/projects')->name('index');

Route::prefix('projects')->group(function () {
    Route::get('/')->name('projects.index')->uses(ProjectsIndexEndpoint::class);
    Route::get('create')->name('projects.create')->uses(ProjectsCreateEndpoint::class);
    Route::get('statistics')->name('projects.statistics')->uses(ProjectsStatisticsEndpoint::class);

    Route::prefix('{project}')->group(function () {
        Route::get('/')->name('projects.show')->uses(ProjectsShowEndpoint::class);
        Route::get('edit')->name('projects.edit')->uses(ProjectsEditEndpoint::class);
        Route::get('restore')->name('projects.restore')->uses(ProjectsRestoreEndpoint::class);

        Route::post('clone')->name('projects.clone')->uses(ProjectsCloneEndpoint::class);
        Route::post('export')->name('projects.export')->uses(ProjectsExportEndpoint::class);
        Route::put('/')->name('projects.update')->uses(ProjectsUpdateEndpoint::class);
        Route::delete('/')->name('projects.destroy')->uses(ProjectsDestroyEndpoint::class);
    });

    Route::post('/')->name('projects.store')->uses(ProjectsStoreEndpoint::class);
});
