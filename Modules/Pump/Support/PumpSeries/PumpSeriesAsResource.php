<?php

namespace Modules\Pump\Support\PumpSeries;

use App\Support\TenantStorage;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Pump\Entities\PumpSeries;

/**
 * Pump series as resource.
 */
final class PumpSeriesAsResource implements Arrayable
{
    /**
     * @var PumpSeries $series
     */
    private PumpSeries $series;

    /**
     * @param PumpSeries $pumpSeries
     */
    public function __construct(PumpSeries $pumpSeries)
    {
        $this->series = $pumpSeries;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return [
            'series' => [
                'data' => [
                    'id' => $this->series->id,
                    'brand_id' => $this->series->brand_id,
                    'name' => $this->series->name,
                    'power_adjustment_id' => $this->series->power_adjustment_id,
                    'category_id' => $this->series->category_id,
                    'types' => $this->series->types()->pluck('pump_types.id')->all(),
                    'applications' => $this->series->applications()->pluck('pump_applications.id')->all(),
                    'is_discontinued' => $this->series->is_discontinued,
                    'image' => $this->series->image,
                    'picture' => (new TenantStorage())->urlToImage($this->series->image),
                ]
            ]
        ];
    }
}
