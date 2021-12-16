<?php

namespace Modules\Selection\Http\Requests\DoublePump;

use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;

class ExportAtOnceDoublePumpSelectionRequest extends ExportAtOnceSelectionRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'selected_pump_name' => ['required', 'string'],
            'pump_id' => ['required', 'exists:tenant.pumps,id'],
            'flow' => ['required', 'numeric'],
            'head' => ['required', 'numeric'],
            'fluid_temperature' => ['required', 'numeric']
        ]);
    }
}
