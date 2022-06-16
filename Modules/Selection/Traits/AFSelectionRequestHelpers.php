<?php

namespace Modules\Selection\Traits;

use App\Rules\ArrayExistsInArray;
use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Pump\Entities\Pump;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

trait AFSelectionRequestHelpers
{
    /**
     * @throws Exception
     */
    public function afSelectionRules(): array
    {
        return array_merge(
            [
                'avr' => ['required', 'boolean'],
                'gate_valves_count' => ['required', new In([0, 1, 2])],
                'kkv' => ['required', 'boolean'],
                'on_street' => ['required', 'boolean'],

                'jockey_flow' => ['sometimes', 'nullable', 'numeric', 'min:0', 'not_in:0'],
                'jockey_head' => ['sometimes', 'nullable', 'numeric', 'min:0', 'not_in:0'],
            ],
            $this->selection_type === SelectionType::getKey(SelectionType::Auto)
                ? ['jockey_series_ids' => ['sometimes', 'nullable', 'array', new ArrayExistsInArray(PumpSeries::all()->pluck('id')->all())]]
                : ['jockey_pump_id' => ['sometimes', 'nullable', new In(Pump::allOrCached()->pluck('id')->all())]]
        );
    }

    /**
     * @return array
     */
    public function afSelectionProps(): array
    {
        return array_merge([
            'station_type' => StationType::AF,

            'gate_valves_count' => $this->gate_valves_count,
            'avr' => $this->avr,
            'kkv' => $this->kkv,
            'on_street' => $this->on_street,

            'jockey_flow' => $this->jockey_flow,
            'jockey_head' => $this->jockey_head
        ], $this->selection_type === SelectionType::getKey(SelectionType::Auto)
            ? [
                'jockey_series_ids' => $this->imploded($this->jockey_series_ids),
                'jockey_brand_ids' => $this->imploded($this->jockey_brand_ids)
            ] : [
                'jockey_pump_id' => $this->jockey_pump_id
            ]
        );
    }
}
