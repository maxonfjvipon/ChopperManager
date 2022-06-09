<?php

/*
|--------------------------------------------------------------------------
| Project Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\Project\Http\Endpoints\EpCreateProject;
use Modules\Project\Http\Endpoints\EpEditProject;
use Modules\Project\Http\Endpoints\EpProjects;
use Modules\Project\Http\Endpoints\EpShowProject;
use Modules\Project\Http\Endpoints\EpStoreProject;
use Modules\Project\Http\Endpoints\EpUpdateProject;

Route::redirect('/', app()->getLocale() . '/projects')->name('default');

Route::prefix('projects')->as('projects.')->group(function () {
    Route::get('/')->name('index')->uses(EpProjects::class);
    Route::get('create')->name('create')->uses(EpCreateProject::class);

    Route::post('/')->name('store')->uses(EpStoreProject::class);

    Route::prefix('{project}')->middleware('auth.project')->group(function () {
        Route::get('edit')->name('edit')->uses(EpEditProject::class);
        Route::get('/')->name('show')->uses(EpShowProject::class);

        Route::put('/')->name('update')->uses(EpUpdateProject::class);
    });
});
