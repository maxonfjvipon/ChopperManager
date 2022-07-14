<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Selection\Entities\Selection;

/**
 * WS Auto selection as resource.
 */
final class WSAutoSelectionAsResource implements Arrayable
{
    use HasIdsArrayFromString;

    /**
     * Ctor.
     *
     * @param Selection $selection
     */
    public function __construct(protected Selection $selection)
    {
    }

    /**
     * @throws Exception
     */
    public function asArray(): array
    {
        return [
            'deviation' => $this->selection->deviation,
            'main_pumps_counts' => $this->idsArrayFromString($this->selection->main_pumps_counts),
            'reserve_pumps_count' => $this->selection->reserve_pumps_count,
            'pump_brand_ids' => $this->idsArrayFromString($this->selection->pump_brand_ids),
            'pump_series_ids' => $this->idsArrayFromString($this->selection->pump_series_ids),
            'collectors' => explode(',', $this->selection->collectors),
        ];
    }
}
