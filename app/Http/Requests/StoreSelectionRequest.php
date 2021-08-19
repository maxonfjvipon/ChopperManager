<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSelectionRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'limit' => $this->limit,
            'power_limit_condition_id' => $this->power_limit_condition_id,
            'power_limit_value' => $this->power_limit_value,
            'between_axes_limit_condition_id' => $this->between_axes_limit_condition_id,
            'between_axes_limit_value' => $this->between_axes_limit_value,
            'dn_input_limit_condition_id' => $this->dn_input_limit_condition_id,
            'dn_input_limit_id' => $this->dn_input_limit_id,
            'dn_output_limit_condition_id' => $this->dn_output_limit_condition_id,
            'dn_output_limit_id' => $this->dn_output_limit_id,
            'pump_regulation_ids' => $this->pump_regulation_ids,
            'pump_type_ids' => $this->pump_type_ids,
            'connection_type_ids' => $this->connection_type_ids,
            'current_phase_ids' => $this->current_phase_ids,
        ]);
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
            'project_id' => ['required', 'exists:projects,id'],
            'pressure' => ['required', 'numeric'],
            'consumption' => ['required', 'numeric'],
            'liquid_temperature' => ['required', 'numeric'],
            'limit' => ['nullable', 'numeric'],
            'backup_pumps_count' => ['required', 'integer'],

            'power_limit_checked' => ['required', 'boolean'],
            'power_limit_condition_id' => ['nullable', 'exists:limit_conditions,id'],
            'power_limit_value' => ['nullable', 'numeric'],

            'between_axes_limit_checked' => ['required', 'boolean'],
            'between_axes_limit_condition_id' => ['nullable', 'exists:limit_conditions,id'],
            'between_axes_limit_value' => ['nullable', 'numeric'],

            'dn_input_limit_checked' => ['required', 'boolean'],
            'dn_input_limit_condition_id' => ['nullable', 'exists:limit_conditions,id'],
            'dn_input_limit_id' => ['nullable', 'exists:dns,id'],

            'dn_output_limit_checked' => ['required', 'boolean'],
            'dn_output_limit_condition_id' => ['nullable', 'exists:limit_conditions,id'],
            'dn_output_limit_id' => ['nullable', 'exists:dns,id'],

            'pump_id' => ['required', 'exists:pumps,id'],
            'selected_pump_name' => ['required', 'string'],
            'pumps_count' => ['required', 'integer', 'min:0', 'not_in:0'],

            'pump_producer_ids' => ['required', 'string'],
            'pump_regulation_ids' => ['nullable', 'string', 'max:3'],
            'pump_type_ids' => ['nullable', 'string'],
            'main_pumps_counts' => ['required', 'string', 'max:20'],
            'connection_type_ids' => ['nullable', 'string'],
            'current_phase_ids' => ['nullable', 'string', 'max:3'],
        ];
    }
}
