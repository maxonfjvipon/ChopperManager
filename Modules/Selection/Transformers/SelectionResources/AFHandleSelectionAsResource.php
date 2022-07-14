<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Selection\Entities\Selection;

final class AFHandleSelectionAsResource extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(private Selection $selection)
    {
        parent::__construct(
            new ArrMerged(
                new WSHandleSelectionAsResource($this->selection),
                new AFSelectionAsResource($this->selection),
                [
                    'jockey_pump_id' => $this->selection->jockey_pump_id,
                    'jockey_brand_id' => $this->selection->jockey_pump?->series->brand_id,
                    'jockey_series_id' => $this->selection->jockey_pump?->series_id,
                ]
            )
        );
    }
}
