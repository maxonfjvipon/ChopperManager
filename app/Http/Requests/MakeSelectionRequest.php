<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeSelectionRequest extends FormRequest
{
    protected function prepareForValidation()
    {
//        $this->merge([
//            'connection_type_ids' => $this->connection_type_ids,
//            'current_phase_ids' => $this->current_phase_ids,
//            'limit' => $this->limit,
//            'power_limit_condition_id' => $this->power_limit_condition_id,
//            'power_limit_value' => $this->power_limit_value,
//            'between_axes_limit_condition_id' => $this->between_axes_limit_condition_id,
//            'between_axes_limit_value' => $this->between_axes_limit_value,
//            'dn_input_limit_condition_id' => $this->dn_input_limit_condition_id,
//            'dn_input_limit_id' => $this->dn_input_limit_id,
//            'dn_output_limit_condition_id' => $this->dn_output_limit_condition_id,
//            'dn_output_limit_id' => $this->dn_output_limit_id,
//            'pump_regulation_ids' => $this->pump_regulation_ids,
//        ]);
    }

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
            'connection_type_ids' => ['sometimes', 'nullable', 'array'],
            'power_adjustment_ids' => ['sometimes', 'nullable', 'array'],

            'main_pumps_counts' => ['required', 'array'],
            'reserve_pumps_count' => ['required', 'integer'],
            'head' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'flow' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'deviation' => ['sometimes', 'nullable', 'numeric'],

            'ptp_length_limit_checked' => ['required', 'boolean'],
            'ptp_length_limit_condition_id' => ['sometimes', 'nullable', 'exists:limit_conditions,id'],
            'ptp_length_limit_value' => ['sometimes', 'nullable', 'min:0'],

            'power_limit_checked' => ['required', 'boolean'],
            'power_limit_condition_id' => ['sometimes', 'nullable', 'exists:limit_conditions,id'],
            'power_limit_value' => ['sometimes', 'nullable', 'min:0'],

            'dn_suction_limit_checked' => ['required', 'boolean'],
            'dn_suction_limit_condition_id' => ['sometimes', 'nullable', 'exists:limit_conditions,id'],
            'dn_suction_limit_id' => ['sometimes', 'nullable', 'exists:dns,id'],

            'dn_pressure_limit_checked' => ['required', 'boolean'],
            'dn_pressure_limit_condition_id' => ['sometimes', 'nullable', 'exists:limit_conditions,id'],
            'dn_pressure_limit_id' => ['sometimes', 'nullable', 'exists:dns,id'],
        ];
    }
}
