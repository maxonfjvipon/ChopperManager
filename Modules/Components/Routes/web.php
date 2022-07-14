<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Components\Http\Endpoints\EpArmature;
use Modules\Components\Http\Endpoints\EpAssemblyJobs;
use Modules\Components\Http\Endpoints\EpChassis;
use Modules\Components\Http\Endpoints\EpCollectors;
use Modules\Components\Http\Endpoints\EpControlSystems;
use Modules\Components\Http\Endpoints\EpImportArmature;
use Modules\Components\Http\Endpoints\EpImportAssemblyJobs;
use Modules\Components\Http\Endpoints\EpImportChassis;
use Modules\Components\Http\Endpoints\EpImportCollectors;
use Modules\Components\Http\Endpoints\EpImportControlSystems;

Route::middleware('admin')->group(function () {
    Route::prefix('control-systems')->as('control_systems.')->group(function () {
        Route::get('/')->name('index')->uses(EpControlSystems::class);
        Route::post('import')->name('import')->uses(EpImportControlSystems::class);
    });

    Route::prefix('armature')->as('armature.')->group(function () {
        Route::get('/')->name('index')->uses(EpArmature::class);
        Route::post('import')->name('import')->uses(EpImportArmature::class);
    });

    Route::prefix('assembly-jobs')->as('assembly_jobs.')->group(function () {
        Route::get('/')->name('index')->uses(EpAssemblyJobs::class);
        Route::post('import')->name('import')->uses(EpImportAssemblyJobs::class);
    });

    Route::prefix('chassis')->as('chassis.')->group(function () {
        Route::get('/')->name('index')->uses(EpChassis::class);
        Route::post('import')->name('import')->uses(EpImportChassis::class);
    });

    Route::prefix('collectors')->as('collectors.')->group(function () {
        Route::get('/')->name('index')->uses(EpCollectors::class);
        Route::post('import')->name('import')->uses(EpImportCollectors::class);
    });
});
