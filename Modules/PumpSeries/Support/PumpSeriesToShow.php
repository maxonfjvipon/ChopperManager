<?php

namespace Modules\PumpSeries\Support;

use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\PumpSeries\Entities\ElPowerAdjustment;
use Modules\PumpSeries\Entities\PumpApplication;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpCategory;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Entities\PumpType;

final class PumpSeriesToShow implements Arrayable
{
    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $brands = PumpBrand::all();
        return (new ArrMerged(
            new ArrObject(
                "filter_data",
                new ArrForFiltering([
                    'brands' => $brands->pluck('name')->all(),
                    'categories' => PumpCategory::allOrCached()->pluck('name')->all(),
                    'power_adjustments' => ElPowerAdjustment::allOrCached()->pluck('name')->all(),
                    'applications' => PumpApplication::allOrCached()->pluck('name')->all(),
                    'types' => PumpType::allOrCached()->pluck('name')->all(),
                ])
            ),
            ['brands' => $brands],
            new ArrObject(
                "series",
                new ArrMapped(
                    PumpSeries::with(['brand', 'category', 'power_adjustment', 'types', 'applications'])
                        ->get()
                        ->all(),
                    fn(PumpSeries $series) => [
                        'id' => $series->id,
                        'brand' => $series->brand->name,
                        'name' => $series->name,
                        'category' => $series->category->name,
                        'power_adjustment' => $series->power_adjustment->name,
                        'applications' => $series->imploded_applications,
                        'types' => $series->imploded_types,
                        'is_discontinued' => $series->is_discontinued,
                    ]
                )
            )
        ))->asArray();
    }
}
