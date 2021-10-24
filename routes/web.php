<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\PumpBrandsController;
use App\Http\Controllers\PumpsController;
use App\Http\Controllers\PumpSeriesController;
use App\Http\Controllers\PumpsPriceListsController;
use App\Http\Controllers\SelectionsController;
use App\Http\Controllers\SetLocaleController;
use App\Http\Controllers\UsersController;
use App\Http\Requests\SetLocaleRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Http\Request;

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
        Route::middleware('guest')->group(function () {
            // LOGIN
            Route::get('login')->name('login')->uses([LoginController::class, 'showLoginForm']);
            Route::post('login')->name('login.attempt')->uses([LoginController::class, 'login']);

            // REGISTER
            Route::get('register')->name('register')->uses([RegisterController::class, 'showRegisterForm']);
            Route::post('register')->name('register.attempt')->uses([RegisterController::class, 'register']);
        });

        // LOGOUT
        Route::post('logout')->name('logout')->uses([LoginController::class, 'logout']);

        // AUTHORIZED
        Route::middleware('auth')->group(function () {

            // EMAIL VERIFICATION
            Route::get('/email/verify')->name('verification.notice')->uses([EmailVerificationController::class, 'notice']);
            Route::get('/email/verify/{id}/{hash}')->name('verification.verify')->middleware('signed')->uses([EmailVerificationController::class, 'verify']);
            Route::post('/email/verification-notification')->name('verification.send')
                ->middleware('throttle:6,1')->uses([EmailVerificationController::class, 'resendVerification']);

            // ONLY WITH VERIFIED EMAIL
            Route::middleware('verified')->group(function () {
                // DASHBOARD
//                Route::get('/dashboard')->name('dashboard')->uses('DashboardController');
                Route::redirect('/', app()->getLocale() . '/projects')->name('index');

                // PROJECTS
                Route::get('projects/{project}/restore')->name('projects.restore')->uses([ProjectsController::class, 'restore']);
                Route::resource('projects', ProjectsController::class);

                // SELECTIONS
                Route::prefix('selections')->group(function () {
                    Route::get('dashboard/{project}')->name('selections.dashboard')->uses([SelectionsController::class, 'dashboard']);
                    Route::get('create/{project}')->name('selections.create')->uses([SelectionsController::class, 'create']);
                    Route::get('show/{selection}')->name('selections.show')->uses([SelectionsController::class, 'show']);
                    Route::get('{selection}/restore')->name('selections.restore')->uses([SelectionsController::class, 'restore']);

                    Route::post('select')->name('selections.select')->uses([SelectionsController::class, 'select']);
                    Route::post('store')->name('selections.store')->uses([SelectionsController::class, 'store']);
                    Route::post('update/{selection}')->name('selections.update')->uses([SelectionsController::class, 'update']);

                    Route::delete('{selection}')->name('selections.destroy')->uses([SelectionsController::class, 'destroy']);
                });

                // PUMP BRANDS
                Route::get('pump_brands/{brand}/restore')->name('pump_brands.restore')->uses([PumpBrandsController::class, 'restore']);
                Route::resource('pump_brands', PumpBrandsController::class)->except(['index', 'show']);

                // PUMP SERIES
                Route::get('pump_series/{series}/restore')->name('pump_series.restore')->uses([PumpSeriesController::class, 'restore']);
                Route::resource('pump_series', PumpSeriesController::class)->except(['show']);

                // USERS
                Route::prefix('users')->group(function () {
                    Route::get('profile')->name('users.profile')->uses([UsersController::class, 'profile']);
                    Route::post('update')->name('users.update')->uses([UsersController::class, 'update']);
                    Route::post('change-password')->name('users.password.change')->uses([UsersController::class, 'changePassword']);
                    Route::post('update-discount')->name('users.discount.update')->uses([UsersController::class, 'updateDiscount']);
                });

                // PUMPS
                Route::post('pumps/lazy-load')->name('pumps.load_lazy')->uses([PumpsController::class, 'loadLazy']);
                Route::post('pumps/import')->name('pumps.import')->uses([PumpsController::class, 'import']);
                Route::resource('pumps', PumpsController::class)->except(['edit', 'create']);

                // PUMPS PRICE LISTS
                Route::post('pumps_price_lists/import')->name('pumps_price_lists.import')->uses(PumpsPriceListsController::class);
            });
        });
    });
