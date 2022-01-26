<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Illuminate\Http\Request;

class SinglePumpSelectionResource extends PumpSelectionResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'head' => $this->head,
            'flow' => $this->flow,
            'fluid_temperature' => $this->fluid_temperature,
            'deviation' => $this->deviation,
            'reserve_pumps_count' => $this->reserve_pumps_count,
            'selected_pump_name' => $this->selected_pump_name,
            'use_additional_filters' => $this->use_additional_filters,
            'range_id' => $this->range_id,

            'power_limit_checked' => $this->power_limit_checked,
            'power_limit_condition_id' => $this->power_limit_condition_id,
            'power_limit_value' => $this->power_limit_value,

            'ptp_length_limit_checked' => $this->ptp_length_limit_checked,
            'ptp_length_limit_condition_id' => $this->ptp_length_limit_condition_id,
            'ptp_length_limit_value' => $this->ptp_length_limit_value,

            'dn_suction_limit_checked' => $this->dn_suction_limit_checked,
            'dn_suction_limit_condition_id' => $this->dn_suction_limit_condition_id,
            'dn_suction_limit_id' => $this->dn_suction_limit_id,

            'dn_pressure_limit_checked' => $this->dn_pressure_limit_checked,
            'dn_pressure_limit_condition_id' => $this->dn_pressure_limit_condition_id,
            'dn_pressure_limit_id' => $this->dn_pressure_limit_id,

            'connection_types' => $this->arrayOfIntsFromString($this->connection_type_ids),
            'power_adjustments' => $this->arrayOfIntsFromString($this->power_adjustment_ids),
            'main_pumps_counts' => $this->arrayOfIntsFromString($this->main_pumps_counts),
            'pump_series' => $this->arrayOfIntsFromString($this->pump_series_ids),
            'pump_brands' => $this->arrayOfIntsFromString($this->pump_brand_ids),
            'mains_connections' => $this->arrayOfIntsFromString($this->mains_connection_ids),
            'pump_types' => $this->arrayOfIntsFromString($this->pump_type_ids),
            'pump_applications' => $this->arrayOfIntsFromString($this->pump_application_ids),
            'custom_range' => $this->arrayOfIntsFromString($this->custom_range ?? "0,100"),

            'to_show' => [
                'name' => $this->selected_pump_name,
                'pump_id' => $this->pump_id,
                'pump_type' => $this->pump_type,
                'pumps_count' => $this->pumps_count,
                'main_pumps_count' => $this->pumps_count - $this->reserve_pumps_count,
                'flow' => $this->flow,
                'head' => $this->head,
                'fluid_temperature' => $this->fluid_temperature,
                'svg' => view(
                    'selection::selection_perf_curves',
                    $this->selectionCurvesData($this->resource)
                )->render(),
//                'pump_info' => $this->pumpInfo(),
            ],
        ];
    }
}
