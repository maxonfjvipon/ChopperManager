<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Exception;

final class WSHandleSelectionAsResource extends SelectionAsResource
{
    /**
     * @return array
     * @throws Exception
     */
    public function asArray(): array
    {
        return [
            'main_pumps_count' => (int)$this->selection->main_pumps_counts,
            'reserve_pumps_count' => $this->selection->reserve_pumps_count,
            'pump_brand_id' => (int)$this->selection->pump_brand_ids,
            'pump_series_id' => (int)$this->selection->pump_series_ids,
            'pump_id' => $this->selection->pump_id,
            'collector' => $this->selection->collectors,
        ];
    }
}
