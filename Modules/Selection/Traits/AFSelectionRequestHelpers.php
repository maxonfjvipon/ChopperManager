<?php

namespace Modules\Selection\Traits;

use Exception;
use Illuminate\Validation\Rules\In;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

trait AFSelectionRequestHelpers
{
    /**
     * @throws Exception
     */
    public function afSelectionRules(): array
    {
        return [
            'avr' => ['required', 'boolean'],
            'gate_valves_count' => ['required', new In([0, 1, 2])],
            'kkv' => ['required', 'boolean'],
            'on_street' => ['required', 'boolean'],

            'jockey_flow' => ['sometimes', 'nullable', 'numeric', 'min:0', 'not_in:0'],
            'jockey_head' => ['sometimes', 'nullable', 'numeric', 'min:0', 'not_in:0'],
            'jockey_pump_id' => ['sometimes', 'nullable', new In(Pump::allOrCached()->pluck('id')->all())]
        ];
    }

    /**
     * @return array
     */
    public function afSelectionProps(): array
    {
        return [
            'station_type' => StationType::AF,

            'gate_valves_count' => $this->gate_valves_count,
            'avr' => $this->avr,
            'kkv' => $this->kkv,
            'on_street' => $this->on_street,

            'jockey_pump_id' => $this->jockey_pump_id,
            'jockey_flow' => $this->jockey_flow,
            'jockey_head' => $this->jockey_head
        ];
    }
}
