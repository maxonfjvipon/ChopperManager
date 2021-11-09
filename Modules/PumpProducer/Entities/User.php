<?php

namespace Modules\PumpProducer\Entities;

use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;

class User extends \Modules\User\Entities\User
{
    protected static function booted()
    {
        static::created(function (self $user) {
            // Create discounts for new registered user
            $user->discounts()->insert(PumpSeries::all()->map(function ($series) use ($user) {
                return ['user_id' => $user->id, 'discountable_id' => $series->id, 'discountable_type' => 'pump_series'];
            })->toArray());
            $user->discounts()->insert(PumpBrand::all()->map(function ($brand) use ($user) {
                return ['user_id' => $user->id, 'discountable_id' => $brand->id, 'discountable_type' => 'pump_brand'];
            })->toArray());
        });
    }
}
