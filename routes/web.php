<?php
//
//use App\Http\Endpoints\Auth\Login\EpLogin;
//use App\Http\Endpoints\Auth\Login\EpLoginAttempt;
//use App\Http\Endpoints\Auth\Login\EpLogout;
//use App\Http\Endpoints\Auth\Register\EpAwait;
//use App\Http\Endpoints\Auth\Register\EpRegister;
//use App\Http\Endpoints\Auth\Register\EpRegisterAttempt;
//use App\Http\Endpoints\Projects\EpCreateProject;
//use App\Http\Endpoints\Projects\EpEditProject;
//use App\Http\Endpoints\Projects\EpProjects;
//use App\Http\Endpoints\Projects\EpStoreProject;
//use App\Http\Endpoints\Projects\EpUpdateProject;
//use Illuminate\Support\Facades\Route;
//
///*
//|--------------------------------------------------------------------------
//| Web Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register web routes for your application. These
//| routes are loaded by the RouteServiceProvider within a group which
//| contains the "web" middleware group. Now create something great!
//|
//*/
//
//// GUEST
//Route::middleware('guest')->group(function () {
//
//    // LOGIN
//    Route::get('login')->name('login')->uses(EpLogin::class);
//    Route::post('login')->name('login.attempt')->uses(EpLoginAttempt::class);
//
//    // REGISTER
//    Route::get('register')->name('register')->uses(EpRegister::class);
//    Route::post('register')->name('register.attempt')->uses(EpRegisterAttempt::class);
//});
//
//// AUTH
//Route::middleware('auth')->group(function() {
//
//    // LOGOUT
//    Route::post('logout')->name('logout')->uses(EpLogout::class);
//
//    // AWAIT
//    Route::get('await')->name('await')->uses(EpAwait::class);
//
//    // VERIFIED
//    Route::middleware('verified')->group(function() {
//
//        // PROJECTS
//        Route::prefix('projects')->group(function() {
//            Route::get('/')->can('project_access')->name('projects.index')->uses(EpProjects::class);
//
//            Route::middleware('can:project_create')->group(function () {
//                Route::get('create')->name('create')->uses(EpCreateProject::class);
//                Route::post('/')->name('store')->uses(EpStoreProject::class);
//            });
//
//            Route::prefix('{project}')->middleware('auth.project')->group(function () {
//                Route::middleware(['can:project_edit'])->group(function () {
//                    Route::get('edit')->name('edit')->uses(EpEditProject::class);
//                    Route::put('/')->name('update')->uses(EpUpdateProject::class);
//                });
//                Route::get('/')->middleware(['can:project_show', 'can:selection_access'])->name('show')->uses(EpShowProject::class);
//                Route::get('restore')->can('project_restore')->name('restore')->uses(EpRestoreProject::class);
//
////                Route::post('clone')->can('project_clone')->name('clone')->uses(EpCloneProject::class);
////                Route::post('export')->can('project_export')->name('export')->uses(EpExportProject::class);
//                Route::delete('/')->can('project_delete')->name('destroy')->uses(EpDestroyProject::class);
//            });
//        });
//    });
//});
//
//// DEFAULT REDIRECT
//Route::redirect('/', '/projects');
