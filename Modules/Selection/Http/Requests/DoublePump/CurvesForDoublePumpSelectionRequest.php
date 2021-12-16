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
            'head' => ['required', 'numeric'],
            'flow' => ['required', 'numeric'],
        ];
    }
}
