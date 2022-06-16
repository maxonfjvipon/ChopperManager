<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Exception;

class WSAutoSelectionAsResource extends SelectionAsResource
{
    /**
     * @return array
     * @throws Exception
     */
    public function asArray(): array
    {
        return [
            'deviation' => $this->selection->deviation,
            'main_pumps_counts' => $this->intsArrayFromString($this->selection->main_pumps_counts),
            'reserve_pumps_count' => $this->selection->reserve_pumps_count,
            'pump_brand_ids' => $this->intsArrayFromString($this->selection->pump_brand_ids),
            'pump_series_ids' => $this->intsArrayFromString($this->selection->pump_series_ids),
            'collectors' => explode(",", $this->selection->collectors),
        ];
    }
}
