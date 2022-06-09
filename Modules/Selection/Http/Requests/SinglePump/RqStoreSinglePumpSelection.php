<?php

namespace Modules\Selection\Http\Requests\SinglePump;

use Modules\Selection\Http\Requests\RqStoreSelection;

final class RqStoreSinglePumpSelection extends RqStoreSelection
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();
        $this->merge([
            'pump_application_ids' => $this->imploded($this->pump_application_ids),
            'main_pumps_counts' => $this->imploded($this->main_pumps_counts),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'head' => ['required', 'numeric'],
            'flow' => ['required', 'numeric'],
            'fluid_temperature' => ['required', 'numeric'],
            'deviation' => ['nullable', 'numeric'],
            'reserve_pumps_count' => ['required', 'integer'],
            'range_id' => ['required', 'exists:selection_ranges,id'],
            'use_additional_filters' => ['required', 'boolean'],

            'power_limit_checked' => ['sometimes', 'required', 'boolean'],
            'power_limit_condition_id' => ['sometimes', 'nullable', 'exists:limit_conditions,id'],
            'power_limit_value' => ['sometimes', 'nullable', 'numeric'],

            'ptp_length_limit_checked' => ['sometimes', 'required', 'boolean'],
            'ptp_length_limit_condition_id' => ['sometimes', 'nullable', 'exists:limit_conditions,id'],
            'ptp_length_limit_value' => ['sometimes', 'nullable', 'numeric'],

            'dn_suction_limit_checked' => ['sometimes', 'required', 'boolean'],
            'dn_suction_limit_condition_id' => ['sometimes', 'nullable', 'exists:limit_conditions,id'],
            'dn_suction_limit_id' => ['sometimes', 'nullable', 'exists:dns,id'],

            'dn_pressure_limit_checked' => ['sometimes', 'required', 'boolean'],
            'dn_pressure_limit_condition_id' => ['sometimes', 'nullable', 'exists:limit_conditions,id'],
            'dn_pressure_limit_id' => ['sometimes', 'nullable', 'exists:dns,id'],

            'pump_id' => ['required', 'exists:pumps,id'],
            'selected_pump_name' => ['required', 'string'],
            'pumps_count' => ['required', 'integer', 'min:0', 'not_in:0'],

            'custom_range' => ['required', 'string', 'max:7'],
            'pump_series_ids' => ['required', 'string'],
            'pump_brand_ids' => ['required', 'string'],
            'mains_connection_ids' => ['nullable', 'string', 'max:3'],
            'pump_type_ids' => ['nullable', 'string'],
            'pump_application_ids' => ['nullable', 'string'],
            'main_pumps_counts' => ['required', 'string', 'max:20'],
            'connection_type_ids' => ['nullable', 'string'],
            'power_adjustment_ids' => ['nullable', 'string', 'max:3'],
        ];
    }
}
