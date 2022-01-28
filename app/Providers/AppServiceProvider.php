<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'pump_brand' => PumpBrand::class,
            'pump_series' => PumpSeries::class,
        ]);
    }
}
