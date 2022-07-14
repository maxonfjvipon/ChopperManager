<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Selection\Entities\Selection;

/**
 * AF selection as resource.
 */
final class AFSelectionAsResource implements Arrayable
{
    /**
     * Ctor.
     */
    public function __construct(private Selection $selection)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function asArray(): array
    {
        return [
            'avr' => $this->selection->avr->value,
            'kkv' => $this->selection->kkv->value,
            'on_street' => $this->selection->on_street->value,
            'gate_valves_count' => $this->selection->gate_valves_count,

            'jockey_flow' => $this->selection->jockey_flow,
            'jockey_head' => $this->selection->jockey_head,
        ];
    }
}
