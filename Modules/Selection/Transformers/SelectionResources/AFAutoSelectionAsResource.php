<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;

final class AFAutoSelectionAsResource extends WSAutoSelectionAsResource
{
    public function asArray(): array
    {
        return ArrMerged::new(
            parent::asArray(),
            new AFSelectionAsResource($this->selection),
            [
                'jockey_brand_ids' => $this->intsArrayFromString($this->selection->jockey_brand_ids),
                'jockey_series_ids' => $this->intsArrayFromString($this->selection->jockey_series_ids),
            ]
        )->asArray();
    }
}
