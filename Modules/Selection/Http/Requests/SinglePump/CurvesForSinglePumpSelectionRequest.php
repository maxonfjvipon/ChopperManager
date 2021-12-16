<?php

namespace Modules\Selection\Http\Requests\SinglePump;

use Modules\Selection\Http\Requests\CurvesForSelectionRequest;

class CurvesForSinglePumpSelectionRequest extends CurvesForSelectionRequest
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
            'pumps_count' => ['required', 'numeric'],
            'main_pumps_count' => ['required', 'numeric'],
            'head' => ['required', 'numeric'],
            'flow' => ['required', 'numeric'],
        ];
    }
}
