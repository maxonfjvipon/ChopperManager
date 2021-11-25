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
    Route::post('{project}/export')->name('projects.export')->uses([ProjectsController::class, 'export']);
    Route::get('{project}/restore')->name('projects.restore')->uses([ProjectsController::class, 'restore']);
});
Route::resource('projects', ProjectsController::class);
