<?php

namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Multitenancy\Models\Tenant;

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
        Route::middleware(['web', 'tenant'])
            ->namespace($this->namespace)
            ->group(function () {
                if (Tenant::checkCurrent()) {
                    Route::domain(Tenant::current()->domain)
                        ->prefix(LaravelLocalization::setLocale())
                        ->middleware(['auth.module', 'auth.active', 'verified', 'localizationRedirect'])
                        ->group(module_path($this->moduleName(), '/Routes/web.php'));
                }
            });
    }
}
