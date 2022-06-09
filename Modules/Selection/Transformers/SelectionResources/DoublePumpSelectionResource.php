<?php


namespace Modules\Selection\Transformers\SelectionResources;

use Exception;

final class DoublePumpSelectionResource extends SelectionResource
{
    /**
     * @throws Exception
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'head' => $this->head,
            'flow' => $this->flow,
            'fluid_temperature' => $this->fluid_temperature,
            'deviation' => $this->deviation,
            'selected_pump_name' => $this->selected_pump_name,
            'use_additional_filters' => $this->use_additional_filters,
            'range_id' => $this->range_id,
            'dp_work_scheme_id' => $this->dp_work_scheme_id,

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
            'pump_series' => $this->arrayOfIntsFromString($this->pump_series_ids),
            'pump_brands' => $this->arrayOfIntsFromString($this->pump_brand_ids),
            'mains_connections' => $this->arrayOfIntsFromString($this->mains_connection_ids),
            'pump_types' => $this->arrayOfIntsFromString($this->pump_type_ids),
            'custom_range' => $this->arrayOfIntsFromString($this->custom_range),

            'to_show' => [
                'name' => $this->selected_pump_name,
                'pump_id' => $this->pump_id,
                'flow' => $this->flow,
                'head' => $this->head,
                'fluid_temperature' => $this->fluid_temperature,
                'dp_work_scheme_id' => $this->dp_work_scheme_id,
            ]
        ];
    }
}
