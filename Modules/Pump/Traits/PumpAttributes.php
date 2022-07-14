<?php

namespace Modules\Pump\Traits;

use App\Models\Enums\Currency;

/**
 * Pump attributes.
 */
trait PumpAttributes
{
    public function getCurrencyAttribute(): Currency
    {
        return $this->series->currency;
    }

    public function getIsDiscontinuedWithSeriesAttribute(): bool
    {
        return $this->series->is_discontinued
            ? true
            : $this->getOriginal('is_discontinued');
    }

//    public function getFullNameAttribute(): string
//    {
//        return "{$this->brand->name} $this->name";
//    }
}
