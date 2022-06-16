<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Selection\Entities\Selection;

final class AFSelectionAsResource implements Arrayable
{
    /**
     * Ctor.
     * @param Selection $selection
     */
    public function __construct(private Selection $selection)
    {
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return [
            'avr' => $this->selection->avr->value,
            'kkv' => $this->selection->kkv->value,
            'on_street' => $this->selection->on_street->value,
            'gate_valves_count' => $this->selection->gate_valves_count,

            'jockey_pump_id' => $this->selection->jockey_pump_id,
            'jockey_flow' => $this->selection->jockey_flow,
            'jockey_head' => $this->selection->jockey_head,
            'jockey_brand_id' => $this->selection->jockey_pump?->series->brand_id,
            'jockey_series_id' => $this->selection->jockey_pump?->series_id,
        ];
    }
}
