<?php

namespace Modules\Project\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

abstract class CoreRouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * @return string
     */
    abstract protected function moduleName(): string;

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::middleware('web')->group(function () {
            Route::namespace($this->namespace)
                ->prefix(LaravelLocalization::setLocale())
                ->middleware(['auth', 'auth.active', 'verified', 'localizationRedirect'])
                ->group(module_path($this->moduleName(), '/Routes/web.php'));
        });
    }
}
