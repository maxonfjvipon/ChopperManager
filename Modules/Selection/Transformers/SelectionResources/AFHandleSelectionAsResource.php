<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;

class AFHandleSelectionAsResource extends WSHandleSelectionAsResource
{
    public function asArray(): array
    {
        return ArrMerged::new(
            parent::asArray(),
            new AFSelectionAsResource($this->selection)
        )->asArray();
    }
}
