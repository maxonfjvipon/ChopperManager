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
use Modules\AdminPanel\Entities\Tenant;

$tenantSelectionsController = Tenant::current()->getControllerClass('SinglePumpSelectionsController');

// SELECTIONS
Route::prefix('sp_selections')->group(function () use ($tenantSelectionsController) {
    Route::prefix('{selection}')->group(function () use ($tenantSelectionsController) {
        Route::post('restore')->name('sp_selections.restore')->uses([$tenantSelectionsController, 'restore']);
        Route::prefix('export')->group(function () use ($tenantSelectionsController) {
            Route::post('/')->name('sp_selections.export')->uses([$tenantSelectionsController, 'export']);
            Route::post('at-once')->name('sp_selections.export.at_once')->uses([$tenantSelectionsController, 'exportAtOnce']);
        });
    });
    Route::post('select')->name('sp_selections.select')->uses([$tenantSelectionsController, 'select']);
    Route::post('curves')->name('sp_selections.curves')->uses([$tenantSelectionsController, 'curves']);
});
Route::get('projects/{project}/selections/dashboard')->name('selections.dashboard')->uses([$tenantSelectionsController, 'index']);
Route::resource('projects.sp_selections', $tenantSelectionsController)->except(['index', 'edit'])->shallow()->parameters([
    'sp_selections' => 'selection'
]);
