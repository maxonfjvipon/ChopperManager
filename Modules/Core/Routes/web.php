<?php

/*
|--------------------------------------------------------------------------
| Core Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\ProjectsController;

// PROJECTS
Route::redirect('/', app()->getLocale() . '/projects')->name('index');
Route::prefix('projects')->group(function () {
    Route::prefix('{project}')->group(function () {
        Route::post('export')->name('projects.export')->uses([ProjectsController::class, 'export']);
        Route::get('restore')->name('projects.restore')->uses([ProjectsController::class, 'restore']);
        Route::post('clone')->name('projects.clone')->uses([ProjectsController::class, 'clone']);
    });
});
Route::resource('projects', ProjectsController::class);
