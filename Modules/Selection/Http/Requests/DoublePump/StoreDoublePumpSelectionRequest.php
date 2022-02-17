<?php

namespace Modules\Selection\Http\Requests\DoublePump;

use Modules\Selection\Http\Requests\SelectionRequest;

final class StoreDoublePumpSelectionRequest extends SelectionRequest
{
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
            'range_id' => ['required', 'exists:tenant.selection_ranges,id'],
            'use_additional_filters' => ['required', 'boolean'],

            'power_limit_checked' => ['sometimes', 'required', 'boolean'],
            'power_limit_condition_id' => ['sometimes', 'nullable', 'exists:tenant.limit_conditions,id'],
            'power_limit_value' => ['sometimes', 'nullable', 'numeric'],

            'ptp_length_limit_checked' => ['sometimes', 'required', 'boolean'],
            'ptp_length_limit_condition_id' => ['sometimes', 'nullable', 'exists:tenant.limit_conditions,id'],
            'ptp_length_limit_value' => ['sometimes', 'nullable', 'numeric'],

            'dn_suction_limit_checked' => ['sometimes', 'required', 'boolean'],
            'dn_suction_limit_condition_id' => ['sometimes', 'nullable', 'exists:tenant.limit_conditions,id'],
            'dn_suction_limit_id' => ['sometimes', 'nullable', 'exists:tenant.dns,id'],

            'dn_pressure_limit_checked' => ['sometimes', 'required', 'boolean'],
            'dn_pressure_limit_condition_id' => ['sometimes', 'nullable', 'exists:tenant.limit_conditions,id'],
            'dn_pressure_limit_id' => ['sometimes', 'nullable', 'exists:tenant.dns,id'],

            'pump_id' => ['required', 'exists:tenant.pumps,id'],
            'selected_pump_name' => ['required', 'string'],
            'dp_work_scheme_id' => ['required', 'exists:tenant.double_pump_work_schemes,id'],

            'custom_range' => ['required', 'string', 'max:7'],
            'pump_series_ids' => ['required', 'string'],
            'pump_brand_ids' => ['required', 'string'],
            'mains_connection_ids' => ['nullable', 'string', 'max:3'],
            'pump_type_ids' => ['nullable', 'string'],
            'connection_type_ids' => ['nullable', 'string'],
            'power_adjustment_ids' => ['nullable', 'string', 'max:3'],
        ];
    }
}
