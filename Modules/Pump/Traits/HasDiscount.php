<?php


namespace Modules\Pump\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Discount;

/**
 * Has discount.
 * @package Modules\Pump\Traits
 */
trait HasDiscount
{
    /**
     * @return MorphOne
     */
    public function discount(): MorphOne
    {
        return $this->morphOne(Discount::class, 'discountable')
            ->where('user_id', Auth::id());
    }
}