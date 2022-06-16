<?php

namespace Modules\Selection\Http\Requests\WS\Handle;

use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Pump\Entities\Pump;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqStoreSelection;
use Modules\Selection\Rules\DnMaterialRegex;

/**
 * @property float $flow
 * @property float $head
 * @property int $main_pumps_count
 * @property int $reserve_pumps_count
 * @property array<int> $control_system_type_ids
 * @property int $pump_brand_id
 * @property int $pump_series_id
 * @property string $collector
 * @property string $comment
 * @property int $pump_id
 */
class RqStoreWSHandleSelection extends RqStoreSelection
{
    /**
     * @return array
     * @throws Exception
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'main_pumps_count' => ['required', new In([1, 2, 3, 4, 5])],
                'pump_brand_id' => ['required', new In(PumpBrand::pluck('id')->all())],
                'pump_series_id' => ['required', new In(PumpSeries::pluck('id')->all())],
                'pump_id' => ['required', new In(Pump::allOrCached()->pluck('id')->all())],
                'collector' => ['required', new DnMaterialRegex()],
            ]
        );
    }

    /**
     * @return array
     */
    public function selectionProps(): array
    {
        return array_merge(
            parent::selectionProps(),
            [
                'main_pumps_counts' => (string)$this->main_pumps_count,
                'pump_brand_ids' => (string)$this->pump_brand_id,
                'pump_series_ids' => (string)$this->pump_series_id,
                'pump_id' => $this->pump_id,
                'collectors' => $this->collector,
                'type' => SelectionType::Handle,
                'station_type' => StationType::WS,
            ]
        );
    }
}
