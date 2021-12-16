<?php

/*
|--------------------------------------------------------------------------
| Selection Module Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Selection\Http\Controllers\SelectionsController;

Route::get('projects/{project}/selections/dashboard')->name('selections.dashboard')->uses([SelectionsController::class, 'index']);
Route::resource('projects.selections', SelectionsController::class)->except(['index', 'edit'])->shallow();

Route::prefix('selections')->group(function () {
    Route::prefix('{selection}')->group(function () {
        Route::get('restore')->name('selections.restore')->uses([SelectionsController::class, 'restore']);
        Route::post('export')->name('selections.export')->uses([SelectionsController::class, 'export']);
    });
    Route::post('export/at-once')->name('selections.export.at_once')->uses([SelectionsController::class, 'exportAtOnce']);
    Route::post('select')->name('selections.select')->uses([SelectionsController::class, 'select']);
    Route::post('curves')->name('selections.curves')->uses([SelectionsController::class, 'curves']);
});
