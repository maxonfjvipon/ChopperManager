<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Modules\Pump\Entities\DoublePump;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\SinglePump;
use Modules\Pump\Traits\MorphPumpable;

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
            Pump::$SINGLE_PUMP => SinglePump::class,
            Pump::$DOUBLE_PUMP => DoublePump::class,
        ]);
    }
}
