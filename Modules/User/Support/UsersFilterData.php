<?php

namespace Modules\User\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\Pump\Entities\PumpSeries;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;

/**
 * Users filter data.
 */
final class UsersFilterData implements Arrayable
{
    public static function new(): UsersFilterData
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return ArrMerged::new(
            [
                'selection_types' => SelectionType::all(['id', 'name']),
                'businesses' => Business::allOrCached()
            ],
            ArrObject::new(
                "series",
                ArrMapped::new(
                    PumpSeries::with('brand')->get()->all(),
                    fn(PumpSeries $series) => [
                        'id' => $series->id,
                        'name' => $series->brand->name . " " . $series->name
                    ]
                )
            ),
            ArrObject::new(
                "countries",
                ArrMapped::new(
                    Country::allOrCached()->all(),
                    fn(Country $country) => [
                        'id' => $country->id,
                        'name' => $country->country_code
                    ]
                )
            ),
        )->asArray();
    }
}
