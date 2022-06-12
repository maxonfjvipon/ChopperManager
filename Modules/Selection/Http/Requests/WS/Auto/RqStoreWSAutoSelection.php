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
 * @property int $reserve_pumps_count
 * @property array<int> $control_system_type_ids
 * @property array<int> $pump_brand_ids
 * @property array<int> $pump_series_ids
 * @property array<string> $collectors
 * @property string $comment
 */
final class RqStoreWSAutoSelection extends RqStoreSelection
{
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'flow' => ['required', 'numeric', 'min:0'],
                'head' => ['required', 'numeric', 'min:0'],
                'deviation' => ['sometimes', 'nullable', 'numeric'],

                'main_pumps_counts' => ['required', 'array', new ArrayExistsInArray([1, 2, 3, 4, 5])],
                'reserve_pumps_count' => ['required', 'numeric', 'min:0', 'max:4'],

                'control_system_type_ids' => ['required', 'array', new ArrayExistsInArray(ControlSystemType::allOrCached()->pluck('id')->all())],
                'pump_brand_ids' => ['required', 'array', new ArrayExistsInArray(PumpBrand::pluck('id')->all())],
                'pump_series_ids' => ['required', 'array', new ArrayExistsInArray(PumpSeries::pluck('id')->all())],
                'collectors' => ['required', 'array', new DnsMaterialsArray()],

                'added_stations' => ['required', 'array', new PumpStationsArray()],

                'comment' => ['sometimes', 'nullable', 'string']
            ]
        );
    }

    /**
     * @return array
     */
    public function selectionProps(): array
    {
        return [
            'flow' => $this->flow,
            'head' => $this->head,
            'deviation' => $this->deviation,
            'main_pumps_counts' => $this->imploded($this->main_pumps_counts),
            'reserve_pumps_count' => $this->reserve_pumps_count,
            'control_system_type_ids' => $this->imploded($this->control_system_type_ids),
            'pump_brand_ids' => $this->imploded($this->pump_brand_ids),
            'pump_series_ids' => $this->imploded($this->pump_series_ids),
            'collectors' => $this->imploded($this->collectors),
            'type' => SelectionType::Auto,
            'station_type' => StationType::WS,
            'comment' => $this->comment
        ];
    }
}
