<?php


namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Discount;

/**
 * Has discount.
 * @package App\Traits
 */
trait HasDiscount
{
    /**
     * @return MorphOne
     */
    public function auth_discount(): MorphOne
    {
        return $this->discount()->where('user_id', Auth::id());

    }

    /**
     * @return MorphOne
     */
    public function discount(): MorphOne
    {
        return $this->morphOne(Discount::class, 'discountable');
    }
}
