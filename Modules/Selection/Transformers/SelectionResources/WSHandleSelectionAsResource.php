<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Exception;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Transformers\RcPumpStation;

final class WSHandleSelectionAsResource extends SelectionAsResource
{
    /**
     * @return array
     * @throws Exception
     */
    public function asArray(): array
    {
        return [
            'id' => $this->selection->id,
            'flow' => $this->selection->flow,
            'head' => $this->selection->head,
            'main_pumps_count' => (int)$this->selection->main_pumps_counts,
            'reserve_pumps_count' => $this->selection->reserve_pumps_count,
            'control_system_type_ids' => $this->intsArrayFromString($this->selection->control_system_type_ids),
            'pump_brand_id' => (int)$this->selection->pump_brand_ids,
            'pump_series_id' => (int)$this->selection->pump_series_ids,
            'pump_id' => $this->selection->pump_id,
            'collector' => $this->selection->collectors,
            'comment' => $this->selection->comment,
            'pump_stations' => RcPumpStation::collection($this->selection->pump_stations_to_show())
        ];
    }
}
