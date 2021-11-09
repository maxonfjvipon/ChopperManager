<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\AdminPanel\Entities\Tenant;
use Modules\User\Http\Controllers\ProfileController;

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

// CHECK LOCALE
Route::prefix(LaravelLocalization::setLocale())
    ->middleware('localizationRedirect')
    ->middleware('localeSessionRedirect')
    ->group(function () {
        if (Tenant::checkCurrent()) {
            Route::domain(Tenant::current()->domain)->group(function () {
//                Route::middleware('guest')->group(function () {
//                    // LOGIN
//                    Route::get('login')->name('login')->uses([LoginController::class, 'showLoginForm']);
//                    Route::post('login')->name('login.attempt')->uses([LoginController::class, 'login']);
//
//                    // REGISTER
//                    Route::get('register')->name('register')->uses([RegisterController::class, 'showRegisterForm']);
//                    Route::post('register')->name('register.attempt')->uses([RegisterController::class, 'register']);
//                });
//
//                // LOGOUT
//                Route::post('logout')->name('logout')->uses([LoginController::class, 'logout']);

                // AUTHORIZED
                Route::middleware('auth.module')->group(function () {

                    // EMAIL VERIFICATION
//                    Route::get('/email/verify')->name('verification.notice')->uses([EmailVerificationController::class, 'notice']);
//                    Route::get('/email/verify/{id}/{hash}')->name('verification.verify')->middleware('signed')->uses([EmailVerificationController::class, 'verify']);
//                    Route::post('/email/verification-notification')->name('verification.send')
//                        ->middleware('throttle:6,1')->uses([EmailVerificationController::class, 'resendVerification']);

                    // ONLY WITH VERIFIED EMAIL
                    Route::middleware('verified')->group(function () {

                        // PROJECTS
//                        Route::redirect('/', app()->getLocale() . '/projects')->name('index');
//                        Route::get('projects/{project}/restore')->name('projects.restore')->uses([ProjectsController::class, 'restore']);
//                        Route::resource('projects', ProjectsController::class);

                        // SELECTIONS
//                        Route::prefix('selections')->group(function () {
//                            Route::get('dashboard/{project}')->name('selections.dashboard')->uses([SelectionsController::class, 'dashboard']);
//                            Route::get('create/{project}')->name('selections.create')->uses([SelectionsController::class, 'create']);
//                            Route::get('show/{selection}')->name('selections.show')->uses([SelectionsController::class, 'show']);
//                            Route::get('{selection}/restore')->name('selections.restore')->uses([SelectionsController::class, 'restore']);
//
//                            Route::post('select')->name('selections.select')->uses([SelectionsController::class, 'select']);
//                            Route::post('store')->name('selections.store')->uses([SelectionsController::class, 'store']);
//                            Route::post('update/{selection}')->name('selections.update')->uses([SelectionsController::class, 'update']);
//
//                            Route::delete('{selection}')->name('selections.destroy')->uses([SelectionsController::class, 'destroy']);
//                        });

//                        // PUMP BRANDS
//                        Route::get('pump_brands/{brand}/restore')->name('pump_brands.restore')->uses([PumpBrandsController::class, 'restore']);
//                        Route::resource('pump_brands', PumpBrandsController::class)->except(['index', 'show']);
//
//                        // PUMP SERIES
//                        Route::get('pump_series/{series}/restore')->name('pump_series.restore')->uses([PumpSeriesController::class, 'restore']);
//                        Route::resource('pump_series', PumpSeriesController::class)->except(['show']);

                        // PROFILE
//                        Route::prefix('profile')->group(function () {
//                            Route::get('index')->name('profile.index')->uses([ProfileController::class, 'index']);
//                            Route::post('update')->name('profile.update')->uses([ProfileController::class, 'update']);
//                            Route::post('change-password')->name('profile.password.change')->uses([ProfileController::class, 'changePassword']);
//                            Route::post('update-discount')->name('profile.discount.update')->uses([ProfileController::class, 'updateDiscount']);
//                        });

                        // USERS
//                        Route::resource('users', UsersController::class);

//                        // PUMPS
//                        Route::prefix('pumps/import')->group(function () {
//                            Route::post('/')->name('pumps.import')->uses([PumpsController::class, 'import']);
//                            Route::post('price_list')->name('pumps.import.price_lists')->uses([PumpsController::class, 'importPriceLists']);
//                        });
//                        Route::resource('pumps', PumpsController::class)->except(['edit', 'create']);
                    });
                });
            });
        }
    });
