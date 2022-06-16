<?php

namespace Modules\Selection\Http\Requests\AF\Auto;

use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\WS\Auto\RqStoreWSAutoSelection;

/**
 * @property int $gate_valves_count
 * @property boolean $avr
 * @property boolean $kkv
 * @property boolean $on_street
 * @property int $jockey_pump_id
 * @property float $jockey_flow
 * @property float $jockey_head
 */
final class RqStoreAFAutoSelection extends RqStoreWSAutoSelection
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
                'jockey_pump_id' => ['sometimes', 'nullable', new In(Pump::allOrCached()->pluck('id')->all())],
                'jockey_flow' => ['sometimes', 'nullable', 'numeric', 'min:0'],
                'jockey_head' => ['sometimes', 'nullable', 'numeric', 'min:0'],

                'gate_valves_count' => ['required', 'numeric', 'in:0,1,2'],
                'avr' => ['required', 'boolean'],
                'kkv' => ['required', 'boolean'],
                'on_street' => ['required', 'boolean']
            ]
        );
    }


    public function selectionProps(): array
    {
        return array_merge(
            parent::selectionProps(),
            [
                'station_type' => StationType::AF,

                'gate_valves_count' => $this->gate_valves_count,
                'avr' => $this->avr,
                'kkv' => $this->kkv,
                'on_street' => $this->on_street,

                'jockey_pump_id' => $this->jockey_pump_id,
                'jockey_flow' => $this->jockey_flow,
                'jockey_head' => $this->jockey_head
            ]
        );
    }
}
