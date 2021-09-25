<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeSelectionRequest extends FormRequest
{
//    protected function prepareForValidation()
//    {
//        dd($this->dn_suction_limit_condition_id);
//        $this->merge([
//            'connection_type_ids' => $this->connection_type_ids,
//            'power_adjustment_ids' => $this->power_adjustment_ids,
//            'deviation' => $this->deviation,
//
//            'power_limit_condition_id' => $this->power_limit_condition_id,
//            'power_limit_value' => $this->power_limit_value,
//
//            'ptp_length_limit_condition_id' => $this->ptp_length_limit_condition_id,
//            'ptp_length_limit_value' => $this->ptp_length_limit_value,
//
//            'dn_suction_limit_condition_id' => $this->dn_suction_limit_condition_id,
//            'dn_suction_limit_id' => $this->dn_suction_limit_id,
//
//            'dn_pressure_limit_condition_id' => $this->dn_pressure_limit_condition_id,
//            'dn_pressure_limit_id' => $this->dn_pressure_limit_id,
//        ]);
//    }

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
            'connection_type_ids' => ['nullable', 'array'],
            'power_adjustment_ids' => ['nullable', 'array'],

            'main_pumps_counts' => ['required', 'array'],
            'reserve_pumps_count' => ['required', 'integer'],
            'head' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'flow' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'deviation' => ['nullable', 'numeric'],

            'ptp_length_limit_checked' => ['required', 'boolean'],
            'ptp_length_limit_condition_id' => ['nullable', 'exists:limit_conditions,id'],
            'ptp_length_limit_value' => ['nullable', 'min:0'],

            'power_limit_checked' => ['required', 'boolean'],
            'power_limit_condition_id' => ['nullable', 'exists:limit_conditions,id'],
            'power_limit_value' => ['nullable', 'min:0'],

            'dn_suction_limit_checked' => ['required', 'boolean'],
            'dn_suction_limit_condition_id' => ['nullable', 'exists:limit_conditions,id'],
            'dn_suction_limit_id' => ['nullable', 'exists:dns,id'],

            'dn_pressure_limit_checked' => ['required', 'boolean'],
            'dn_pressure_limit_condition_id' => ['nullable', 'exists:limit_conditions,id'],
            'dn_pressure_limit_id' => ['nullable', 'exists:dns,id'],
        ];
    }
}
