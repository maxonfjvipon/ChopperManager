<?php

namespace Modules\User\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Selection\Entities\SelectionType;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;

/**
 * Users filter data.
 */
final class UsersFilterData implements Arrayable
{
    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return (new ArrMerged(
            [
                'selection_types' => SelectionType::all(['id', 'name']),
                'businesses' => Business::allOrCached()
            ],
            new ArrObject(
                "series",
                new ArrMapped(
                    PumpSeries::with('brand')->get()->all(),
                    fn(PumpSeries $series) => [
                        'id' => $series->id,
                        'name' => $series->brand->name . " " . $series->name
                    ]
                )
            ),
            new ArrObject(
                "countries",
                new ArrMapped(
                    Country::allOrCached()->all(),
                    fn(Country $country) => [
                        'id' => $country->id,
                        'name' => $country->country_code
                    ]
                )
            ),
        ))->asArray();
    }
}
