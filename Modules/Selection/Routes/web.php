<?php

/*
|--------------------------------------------------------------------------
| Selection module Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Selection\Http\Controllers\SelectionsController;

$tenantSelectionsController = Tenant::current()->getControllerClass('SelectionsController');

// SELECTIONS
Route::prefix('selections')->group(function () use ($tenantSelectionsController) {
    Route::get('dashboard/{project}')->name('selections.dashboard')->uses([$tenantSelectionsController, 'dashboard']);
    Route::prefix('pump')->group(function () use ($tenantSelectionsController) {
        Route::get('create-single/{project}')->name('selections.pump.single.create')->uses([$tenantSelectionsController, 'create']);
        Route::get('show-single/{selection}')->name('selections.pump.single.show')->uses([$tenantSelectionsController, 'show']);
        Route::get('{selection}/restore')->name('selections.pump.single.restore')->uses([SelectionsController::class, 'restore']);
        Route::post('store-single')->name('selections.pump.single.store')->uses([SelectionsController::class, 'store']);
        Route::post('select-single')->name('selections.pump.single.select')->uses([SelectionsController::class, 'select']);
        Route::post('update-single/{selection}')->name('selections.pump.single.update')->uses([SelectionsController::class, 'update']);
        Route::delete('single/{selection}')->name('selections.pump.single.destroy')->uses([SelectionsController::class, 'destroy']);
//        Route::get('create-double/{project}')->name('selections.pump.double.create')->uses([SelectionsController::class,'create']);
//        Route::get('show-double/{selection}')->name('selections.pump.double.show')->uses([SelectionsController::class, 'show']);
    });
});
