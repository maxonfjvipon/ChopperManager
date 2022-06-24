<?php

namespace App\Traits;

use App\Models\Enums\Currency;
use App\Interfaces\Rates;

trait HasPriceByRates
{
    public function priceByRates(Rates $rates): float
    {
        return $rates->hasTheSameBaseAs($this->currency)
            ? $this->price
            : round( $this->price / $rates->rateFor($this->currency->key), 2);
    }
}
