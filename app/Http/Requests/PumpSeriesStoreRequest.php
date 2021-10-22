<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PumpSeriesStoreRequest extends FormRequest
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

    public function getSeriesFields(): array
    {
        return [
            'name' => $this->name,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'power_adjustment_id' => $this->power_adjustment_id
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'exists:pump_brands,id'],
            'name' => ['required', 'unique:pump_series,name,' . $this->pump_series->id], // TODO: unique only for current brand
            'power_adjustment_id' => ['required', 'exists:electronic_power_adjustments,id'],
            'category_id' => ['required', 'exists:pump_categories,id'],
            'types' => ['array'], // TODO: every elem in array should exists in DB
            'applications' => ['array'], // TODO: every elem in array should exists in DB
        ];
    }
}
