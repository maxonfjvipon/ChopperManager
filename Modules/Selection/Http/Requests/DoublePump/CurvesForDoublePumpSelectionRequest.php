<?php

namespace Modules\Selection\Http\Requests\DoublePump;

use Modules\Selection\Http\Requests\CurvesForSelectionRequest;

class CurvesForDoublePumpSelectionRequest extends CurvesForSelectionRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'pump_id' => ['required', 'exists:tenant.pumps,id'],
            'pumpable_type' => ['required', 'string'],
            'dp_work_scheme_id' => ['sometimes', 'required', 'nullable', 'exists:tenant.double_pump_work_schemes,id'],
            'head' => ['sometimes', 'nullable', 'numeric'],
            'flow' => ['sometimes', 'nullable', 'numeric'],
        ];
    }
}
