<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SinglePumpSelectionResource extends JsonResource
{
    /*
     * Transform string to array of integers
     */
    private function arrayOfIntsFromString($string): array
    {
        return $string === null ? [] : array_map('intval', explode($this->separator, $string));
    }

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
            'pressure' => $this->pressure,
            'consumption' => $this->consumption,
            'liquid_temperature' => $this->liquid_temperature,
            'limit' => $this->limit,
            'backup_pumps_count' => $this->backup_pumps_count,
            'selected_pump_name' => $this->selected_pump_name,

            'power_limit_checked' => $this->power_limit_checked,
            'power_limit_condition_id' => $this->powerLimitCondition->id ?? null,
            'power_limit_value' => $this->power_limit_value,

            'between_axes_limit_checked' => $this->between_axes_limit_checked,
            'between_axes_limit_condition_id' => $this->betweenAxesLimitCondition->id ?? null,
            'between_axes_limit_value' => $this->betwee_axes_limit_value,

            'dn_input_limit_checked' => $this->dn_input_limit_checked,
            'dn_input_limit_condition_id' => $this->dnInputLimitCondition->id ?? null,
            'dn_input_limit_id' => $this->dnInputLimit->id ?? null,

            'dn_output_limit_checked' => $this->dn_output_limit_checked,
            'dn_output_limit_condition_id' => $this->dnOutputLimitCondition->id ?? null,
            'dn_output_limit_id' => $this->dnOutputLimit->id ?? null,

            'connection_types' => $this->arrayOfIntsFromString($this->connection_type_ids),
            'phases' => $this->arrayOfIntsFromString($this->current_phase_ids),
            'main_pumps_counts' => $this->arrayOfIntsFromString($this->main_pumps_counts),
            'pump_producers' => $this->arrayOfIntsFromString($this->pump_producer_ids),
            'pump_regulations' => $this->arrayOfIntsFromString($this->pump_regulation_ids),
            'pump_types' => $this->arrayOfIntsFromString($this->pump_type_ids),
            'pump_applications' => $this->arrayOfIntsFromString($this->pump_application_ids)

        ];
    }
}
