<?php

namespace Modules\PumpProducer\Entities;

use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
use Modules\User\Entities\Discount;
use Modules\User\Entities\Userable;

class PPUser extends Userable
{
    protected $fillable = [
        'id', 'first_name', 'middle_name', 'last_name', 'email', 'password',
        'country_id', 'currency_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:d.m.Y H:i',
    ];

    protected static function booted()
    {
        static::created(function (self $user) {
            // Create discounts for new registered user
            Discount::updateForUser(PumpSeries::pluck('id')->all(), $user);
        });
    }
}
