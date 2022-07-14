<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Selection\Entities\Selection;

/**
 * AF auto selection as resource.
 */
final class AFAutoSelectionAsResource extends ArrEnvelope
{
    use HasIdsArrayFromString;

    /**
     * Ctor.
     */
    public function __construct(private Selection $selection)
    {
        parent::__construct(
            new ArrMerged(
                new WSAutoSelectionAsResource($this->selection),
                new AFSelectionAsResource($this->selection),
                [
                    'jockey_brand_ids' => $this->idsArrayFromString($this->selection->jockey_brand_ids),
                    'jockey_series_ids' => $this->idsArrayFromString($this->selection->jockey_series_ids),
                ]
            )
        );
    }
}
