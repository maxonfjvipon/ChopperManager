<?php

/*
|--------------------------------------------------------------------------
| Project Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\Project\Http\Endpoints\ProjectsCloneEndpoint;
use Modules\Project\Http\Endpoints\ProjectsCreateEndpoint;
use Modules\Project\Http\Endpoints\ProjectsDestroyEndpoint;
use Modules\Project\Http\Endpoints\ProjectsEditEndpoint;
use Modules\Project\Http\Endpoints\ProjectsExportEndpoint;
use Modules\Project\Http\Endpoints\ProjectsIndexEndpoint;
use Modules\Project\Http\Endpoints\ProjectsRestoreEndpoint;
use Modules\Project\Http\Endpoints\ProjectsShowEndpoint;
use Modules\Project\Http\Endpoints\ProjectsStatisticsEndpoint;
use Modules\Project\Http\Endpoints\ProjectsStoreEndpoint;
use Modules\Project\Http\Endpoints\ProjectsUpdateEndpoint;

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
