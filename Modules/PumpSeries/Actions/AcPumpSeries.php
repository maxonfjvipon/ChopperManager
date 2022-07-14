<?php

namespace Modules\PumpSeries\Actions;

use App\Models\Enums\Country;
use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpSeries;

/**
 * Pump series action.
 */
final class AcPumpSeries extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'filter_data',
                    new ArrForFiltering([
                        'brands' => ($brands = PumpBrand::all())->pluck('name')->all(),
                        'countries' => $brands->unique('country')
                            ->pluck('country')
                            ->map(fn (Country $country) => $country->description)
                            ->all(),
                    ])
                ),
                [
                    'brands' => array_map(
                        fn (PumpBrand $brand) => [
                            'id' => $brand->id,
                            'name' => $brand->name,
                            'country' => $brand->country->description,
                        ],
                        $brands->all(),
                    ),
                    'series' => array_map(
                        fn (PumpSeries $series) => [
                            'id' => $series->id,
                            'brand' => $series->brand->name,
                            'name' => $series->name,
                            'currency' => $series->currency->key,
                            'is_discontinued' => $series->is_discontinued,
                        ],
                        PumpSeries::with('brand')->get()->all(),
                    ),
                ]
            )
        );
    }
}
