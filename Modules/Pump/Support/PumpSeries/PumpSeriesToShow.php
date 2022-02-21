<?php

namespace Modules\Pump\Support\PumpSeries;

use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpType;

final class PumpSeriesToShow implements Arrayable
{
    /**
     * @return PumpSeriesToShow
     */
    public static function new(): PumpSeriesToShow
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $brands = PumpBrand::all();
        return ArrMerged::new(
            ArrObject::new(
                "filter_data",
                ArrForFiltering::new(
                    [
                        'brands' => $brands->pluck('name')->all(),
                        'categories' => PumpCategory::allOrCached()->pluck('name')->all(),
                        'power_adjustments' => ElPowerAdjustment::allOrCached()->pluck('name')->all(),
                        'applications' => PumpApplication::allOrCached()->pluck('name')->all(),
                        'types' => PumpType::allOrCached()->pluck('name')->all(),
                    ]
                )
            ),
            ['brands' => $brands],
            ArrObject::new(
                "series",
                ArrMapped::new(
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
        )->asArray();
    }
}
