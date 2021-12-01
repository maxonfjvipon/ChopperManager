<?php

namespace Modules\Selection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeSinglePumpSelectionRequest extends FormRequest
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
            'series_ids' => ['required', 'array'],

            'main_pumps_counts' => ['required', 'array'],
            'reserve_pumps_count' => ['required', 'integer'],
            'head' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'flow' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'deviation' => ['sometimes', 'nullable', 'numeric'],
            'fluid_temperature' => ['required', 'numeric'],
            'range_id' => ['required', 'exists:tenant.selection_ranges,id'], // fixme
            'custom_range' => ['required', 'array'],
            'use_additional_filters' => ['required', 'boolean'],

            'connection_type_ids' => ['sometimes', 'nullable', 'array'],
            'mains_connection_ids' => ['sometimes', 'nullable', 'array'],

            'ptp_length_limit_checked' => ['sometimes', 'required', 'boolean'],
            'ptp_length_limit_condition_id' => ['sometimes', 'nullable', 'exists:tenant.limit_conditions,id'],
            'ptp_length_limit_value' => ['sometimes', 'nullable', 'min:0'],

            'power_limit_checked' => ['sometimes', 'required', 'boolean'],
            'power_limit_condition_id' => ['sometimes', 'nullable', 'exists:tenant.limit_conditions,id'],
            'power_limit_value' => ['sometimes', 'nullable', 'min:0'],

            'dn_suction_limit_checked' => ['sometimes', 'required', 'boolean'],
            'dn_suction_limit_condition_id' => ['sometimes', 'nullable', 'exists:tenant.limit_conditions,id'],
            'dn_suction_limit_id' => ['sometimes', 'nullable', 'exists:tenant.dns,id'],

            'dn_pressure_limit_checked' => ['sometimes', 'required', 'boolean'],
            'dn_pressure_limit_condition_id' => ['sometimes', 'nullable', 'exists:tenant.limit_conditions,id'],
            'dn_pressure_limit_id' => ['sometimes', 'nullable', 'exists:tenant.dns,id'],
        ];
    }
}
