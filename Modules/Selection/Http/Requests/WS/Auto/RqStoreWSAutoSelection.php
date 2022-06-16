<?php

namespace Modules\Selection\Http\Requests\WS\Auto;

use App\Rules\ArrayExistsInArray;
use Modules\Components\Entities\ControlSystemType;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqStoreSelection;
use Modules\Selection\Rules\DnsMaterialsArray;
use Modules\Selection\Rules\PumpStationsArray;

/**
 * @property float $flow
 * @property float $head
 * @property float $deviation
 * @property array<int> $main_pumps_counts
 * @property array<int> $control_system_type_ids
 * @property array<int> $pump_brand_ids
 * @property array<int> $pump_series_ids
 * @property array<string> $collectors
 * @property string $comment
 */
class RqStoreWSAutoSelection extends RqStoreSelection
{
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'deviation' => ['sometimes', 'nullable', 'numeric'],
                'main_pumps_counts' => ['required', 'array', new ArrayExistsInArray([1, 2, 3, 4, 5])],
                'pump_brand_ids' => ['required', 'array', new ArrayExistsInArray(PumpBrand::pluck('id')->all())],
                'pump_series_ids' => ['required', 'array', new ArrayExistsInArray(PumpSeries::pluck('id')->all())],
                'collectors' => ['required', 'array', new DnsMaterialsArray()],
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
                'deviation' => $this->deviation,
                'main_pumps_counts' => $this->imploded($this->main_pumps_counts),
                'pump_brand_ids' => $this->imploded($this->pump_brand_ids),
                'pump_series_ids' => $this->imploded($this->pump_series_ids),
                'collectors' => $this->imploded($this->collectors),
                'type' => SelectionType::Auto,
                'station_type' => StationType::WS,
            ]
        );
    }
}
