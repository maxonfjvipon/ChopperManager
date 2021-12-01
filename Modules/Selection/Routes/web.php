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
use Modules\Selection\Http\Controllers\SinglePumpSelectionsController;

// SELECTIONS
Route::prefix('sp_selections')->group(function () {
    Route::prefix('{selection}')->group(function () {
        Route::get('restore')->name('sp_selections.restore')->uses([SinglePumpSelectionsController::class, 'restore']);
        Route::post('export')->name('sp_selections.export')->uses([SinglePumpSelectionsController::class, 'export']);
    });
    Route::post('export/at-once')->name('sp_selections.export.at_once')->uses([SinglePumpSelectionsController::class, 'exportAtOnce']);
    Route::post('select')->name('sp_selections.select')->uses([SinglePumpSelectionsController::class, 'select']);
    Route::post('curves')->name('sp_selections.curves')->uses([SinglePumpSelectionsController::class, 'curves']);
});
Route::get('projects/{project}/selections/dashboard')->name('selections.dashboard')->uses([SinglePumpSelectionsController::class, 'index']);
Route::resource('projects.sp_selections', SinglePumpSelectionsController::class)->except(['index', 'edit'])
    ->shallow()
    ->parameters([
        'sp_selections' => 'selection'
    ]);
