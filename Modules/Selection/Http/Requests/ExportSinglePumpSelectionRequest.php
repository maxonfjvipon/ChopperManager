<?php

namespace Modules\Selection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportSinglePumpSelectionRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'print_pump_image' => filter_var($this->print_pump_image, FILTER_VALIDATE_BOOLEAN),
            'print_pump_sizes_image' => filter_var($this->print_pump_sizes_image, FILTER_VALIDATE_BOOLEAN),
            'print_pump_electric_diagram_image' => filter_var($this->print_pump_electric_diagram_image, FILTER_VALIDATE_BOOLEAN),
            'print_pump_cross_sectional_drawing_image' => filter_var($this->print_pump_cross_sectional_drawing_image, FILTER_VALIDATE_BOOLEAN),
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
            'print_pump_image' => ['required', 'boolean'],
            'print_pump_sizes_image' => ['required', 'boolean'],
            'print_pump_electric_diagram_image' => ['required', 'boolean'],
            'print_pump_cross_sectional_drawing_image' => ['required', 'boolean'],
        ];
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
}
