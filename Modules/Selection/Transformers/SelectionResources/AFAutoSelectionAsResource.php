<?php

namespace Modules\Selection\Transformers\SelectionResources;

final class AFAutoSelectionAsResource extends WSAutoSelectionAsResource
{
    public function asArray(): array
    {
        return array_merge(
            parent::asArray(),
            [
                'avr' => $this->selection->avr->value,
                'kkv' => $this->selection->kkv->value,
                'on_street' => $this->selection->on_street->value,
                'gate_valves_count' => $this->selection->gate_valves_count,

                'jockey_pump_id' => $this->selection->jockey_pump_id,
                'jockey_flow' => $this->selection->jockey_flow,
                'jockey_head' => $this->selection->jockey_head,
                'jockey_brand_id' => $this->selection->jockey_pump?->series->brand_id,
                'jockey_series_id' => $this->selection->jockey_pump?->series_id,
            ]
        );
    }
}
