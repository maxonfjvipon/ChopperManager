<?php

namespace Modules\PumpSeries\Actions;

use App\Models\Enums\Country;
use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpSeries;

final class AcPumpSeries
{
    public function __invoke(): Arrayable
    {
        return new ArrMerged(
            new ArrObject(
                "filter_data",
                new ArrForFiltering([
                    'brands' => ($brands = PumpBrand::all())->pluck('name')->all(),
                    'countries' => $brands->unique('country')
                        ->pluck('country')
                        ->map(fn(Country $country) => $country->description)
                        ->all(),
                ])
            ),
            new ArrObject(
                'brands',
                new ArrMapped(
                    $brands->all(),
                    fn(PumpBrand $brand) => [
                        'id' => $brand->id,
                        'name' => $brand->name,
                        'country' => $brand->country->description
                    ]
                )
            ),
            new ArrObject(
                "series",
                new ArrMapped(
                    PumpSeries::with('brand')->get()->all(),
                    fn(PumpSeries $series) => [
                        'id' => $series->id,
                        'brand' => $series->brand->name,
                        'name' => $series->name,
                        'currency' => $series->currency->key,
                        'is_discontinued' => $series->is_discontinued,
                    ]
                )
            )
        );
    }
}
