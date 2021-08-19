<?php

use App\Http\Controllers\PumpsController;
use App\Http\Controllers\SelectionsController;
use Illuminate\Support\Facades\Route;

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

// AUTH
Route::middleware('guest')->group(function () {
    // LOGIN
    Route::get('login')->name('login')->uses('Auth\LoginController@showLoginForm');
    Route::post('login')->name('login.attempt')->uses('Auth\LoginController@login');
    // REGISTER
    Route::get('register')->name('register')->uses('Auth\RegisterController@showRegisterForm');
    Route::post('register')->name('register.attempt')->uses('Auth\RegisterController@register');
});

// LOGOUT
Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');

Route::middleware('auth')->group(function () {
    // DASHBOARD
    Route::get('/dashboard')->name('dashboard')->uses('DashboardController');
    Route::redirect('/', '/dashboard');

    // PROJECTS
    Route::resource('projects', 'ProjectsController')->except(['edit', 'create']);

    // SELECTIONS
    // FIXME: fake selection path
    Route::prefix('selections')->group(function () {
        Route::get('dashboard/{project}')->name('selections.dashboard')->uses('SelectionsController@dashboard');
        Route::get('create/{project}')->name('selections.create')->uses('SelectionsController@create');
        Route::get('show/{selection}')->name('selections.show')->uses('SelectionsController@show');
        Route::get('{project}')->name('selections.fake')->uses('SelectionsController@fake');

        Route::post('select')->name('selections.select')->uses('SelectionsController@select');
        Route::post('store')->name('selections.store')->uses('SelectionsController@store');
        Route::post('update/{selection}')->name('selections.update')->uses('SelectionsController@update');

        Route::delete('{selection}')->name('selections.destroy')->uses('SelectionsController@destroy');
    });

    // USERS
    Route::prefix('users')->group(function () {
        Route::get('profile')->name('users.profile')->uses('UsersController@profile');
        Route::put('update')->name('users.update')->uses('UsersController@update');
    });

    // PUMPS
    Route::post('pumps/import')->name('pumps.import')->uses('PumpsController@import');
    Route::resource('pumps', 'PumpsController')->except(['edit', 'create']);
});
