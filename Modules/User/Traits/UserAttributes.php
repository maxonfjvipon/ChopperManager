<?php

namespace Modules\User\Traits;

use Exception;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFiltered;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrValues;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;
use Modules\Pump\Entities\PumpBrand;

trait UserAttributes
{
    /**
     * @throws Exception
     */
    public function getFullNameAttribute(): string
    {
        return (new TxtImploded(
            " ",
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ))->asString();
    }

    /**
     * @return string
     */
    public function getLastNameAttribute(): string
    {
        return $this->last_name ?? "";
    }

    /**
     * @throws Exception
     */
    public function getFormattedDiscountsAttribute(): array
    {
        return (new ArrValues(
            new ArrMapped(
                new ArrFiltered(
                    $this->discounts()
                        ->where('discountable_type', 'pump_brand')
                        ->with(['discountable' => function (MorphTo $morphTo) {
                            $morphTo->morphWith([
                                PumpBrand::class => [
                                    'series',
                                    'series.discount' => function ($query) {
                                        $query->where('user_id', $this->id);
                                    }
                                ]
                            ]);
                        }])
                        ->get()
                        ->all(),
                    fn($discount) => $discount->discountable
                ),
                fn($discount) => [
                    'key' => $discount->discountable_id . '-' . $discount->discountable_type . '-' . $discount->user_id,
                    'discountable_id' => $discount->discountable_id,
                    'discountable_type' => $discount->discountable_type,
                    'user_id' => $discount->user_id,
                    'name' => $discount->discountable->name,
                    'value' => $discount->value,
                    'children' => (new ArrValues(
                        new ArrMapped(
                            new ArrFiltered(
                                [...$discount->discountable->series],
                                fn($series) => $series->discount
                            ),
                            fn($series) => [
                                'key' => $series->discount->discountable_id
                                    . '-' . $series->discount->discountable_type
                                    . '-' . $series->discount->user_id,
                                'discountable_id' => $series->discount->discountable_id,
                                'discountable_type' => $series->discount->discountable_type,
                                'user_id' => $series->discount->user_id,
                                'name' => $series->name,
                                'value' => $series->discount->value,
                            ]
                        )
                    ))->asArray(),
                ]
            )
        ))->asArray();
    }
}
