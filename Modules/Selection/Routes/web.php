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
use Modules\Selection\Http\Controllers\SinglePumpSelectionController;

$tenantSelectionsController = Tenant::current()->getControllerClass('SelectionsController');
$tenantSinglePumpSelectionController = Tenant::current()->getControllerClass('SinglePumpSelectionController');

//Route::resource('projects.sp_selections', SinglePumpSelectionController::class);

// SELECTIONS
Route::prefix('selections')->group(function () use ($tenantSelectionsController) {
    Route::get('dashboard/{project}')->name('selections.dashboard')->uses([$tenantSelectionsController, 'dashboard']);
    Route::prefix('pump')->group(function () use ($tenantSelectionsController) {
        Route::prefix('single')->group(function () use ($tenantSelectionsController) {
            Route::get('create/{project}')->name('selections.pump.single.create')->uses([$tenantSelectionsController, 'create']);
            Route::get('show/{selection}')->name('selections.pump.single.show')->uses([$tenantSelectionsController, 'show']);
            Route::post('store')->name('selections.pump.single.store')->uses([SelectionsController::class, 'store']);
            Route::post('update/{selection}')->name('selections.pump.single.update')->uses([SelectionsController::class, 'update']);
            Route::get('restore/{selection}')->name('selections.pump.single.restore')->uses([SelectionsController::class, 'restore']);
            Route::delete('{selection}')->name('selections.pump.single.destroy')->uses([SelectionsController::class, 'destroy']);
            Route::post('select')->name('selections.pump.single.select')->uses([SelectionsController::class, 'select']);
            Route::post('export/{selection}')->name('selections.pump.single.export')->uses([SelectionsController::class, 'export']);
            Route::post('export-in-moment')->name('selections.pump.single.export_in_moment')->uses([SelectionsController::class,
                'exportInMoment']);
        });
        Route::post('curves')->name('selections.pump.curves')->uses([SelectionsController::class, 'curves']);
//        Route::get('create-single/{project}')->name('selections.pump.single.create')->uses([$tenantSelectionsController, 'create']);
//        Route::get('show-single/{selection}')->name('selections.pump.single.show')->uses([$tenantSelectionsController, 'show']);
//        Route::get('create-double/{project}')->name('selections.pump.double.create')->uses([SelectionsController::class,'create']);
//        Route::get('show-double/{selection}')->name('selections.pump.double.show')->uses([SelectionsController::class, 'show']);
    });
});
