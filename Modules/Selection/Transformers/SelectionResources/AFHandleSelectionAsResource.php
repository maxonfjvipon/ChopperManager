<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;

class AFHandleSelectionAsResource extends WSHandleSelectionAsResource
{
    public function asArray(): array
    {
        return ArrMerged::new(
            parent::asArray(),
            new AFSelectionAsResource($this->selection),
            [
                'jockey_pump_id' => $this->selection->jockey_pump_id,
                'jockey_brand_id' => $this->selection->jockey_pump?->series->brand_id,
                'jockey_series_id' => $this->selection->jockey_pump?->series_id,
            ]
        )->asArray();
    }
}
