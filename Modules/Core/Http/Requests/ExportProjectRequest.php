<?php

namespace Modules\Core\Http\Requests;

use App\Rules\ExistsInArray;
use App\Rules\ExistsInIdsArray;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Selection\Entities\Selection;

final class ExportProjectRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'retail_price' => filter_var($this->retail_price, FILTER_VALIDATE_BOOLEAN),
            'personal_price' => filter_var($this->personal_price, FILTER_VALIDATE_BOOLEAN),
            'pump_image' => filter_var($this->pump_image, FILTER_VALIDATE_BOOLEAN),
            'pump_sizes_image' => filter_var($this->pump_sizes_image, FILTER_VALIDATE_BOOLEAN),
            'pump_electric_diagram_image' => filter_var($this->pump_electric_diagram_image, FILTER_VALIDATE_BOOLEAN),
            'pump_cross_sectional_drawing_image' => filter_var($this->pump_cross_sectional_drawing_image, FILTER_VALIDATE_BOOLEAN),
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
            'selection_ids' => ['required', 'array'], // todo: every item should exists in database
            'retail_price' => ['required', 'boolean'],
            'personal_price' => ['required', 'boolean'],
            'pump_image' => ['required', 'boolean'],
            'pump_sizes_image' => ['required', 'boolean'],
            'pump_electric_diagram_image' => ['required', 'boolean'],
            'pump_cross_sectional_drawing_image' => ['required', 'boolean'],
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
