<?php

namespace Modules\Selection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSinglePumpSelectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
//            'project_id' => ['required', 'exists:tenant.projects,id'],
            'head' => ['required', 'numeric'],
            'flow' => ['required', 'numeric'],
            'fluid_temperature' => ['required', 'numeric'],
            'deviation' => ['nullable', 'numeric'],
            'reserve_pumps_count' => ['required', 'integer'],
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
            'pumps_count' => ['required', 'integer', 'min:0', 'not_in:0'],

            'custom_range' => ['required', 'string', 'max:7'],
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
