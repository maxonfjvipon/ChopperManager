<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Exception;
use Illuminate\Http\Request;
use Modules\Selection\Support\TxtSelectionCurvesView;
use Modules\Selection\Transformers\RcPumpStation;

final class WaterAutoSelectionAsResource extends SelectionAsResource
{
    public function asArray(): array
    {
        return [
            'id' => $this->selection->id,
            'flow' => $this->selection->flow,
            'head' => $this->selection->head,
            'deviation' => $this->selection->deviation,
            'main_pumps_counts' => $this->intsArrayFromString($this->selection->main_pumps_counts),
            'reserve_pumps_count' => $this->selection->reserve_pumps_count,
            'control_system_type_ids' => $this->intsArrayFromString($this->selection->control_system_type_ids),
            'pump_brand_ids' => $this->intsArrayFromString($this->selection->pump_brand_ids),
            'pump_series_ids' => $this->intsArrayFromString($this->selection->pump_series_ids),
            'collectors' => explode(",", $this->selection->collectors),
            'comment' => $this->selection->comment,
            'pump_stations' => RcPumpStation::collection($this->selection
                ->pump_stations()
                ->with([
                    'control_system' => function ($query) {
                        $query->select('id', 'type_id');
                    },
                    'control_system.type' => function ($query) {
                        $query->select('id', 'name', 'station_type');
                    },
                    'pump' => function ($query) {
                        $query->select('id', 'name', 'series_id');
                    },
                    'pump.series' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'input_collector' => function ($query) {
                        $query->select('id', 'dn_common', 'material');
                    }
                ])
                ->get()
            )
        ];
    }
}
