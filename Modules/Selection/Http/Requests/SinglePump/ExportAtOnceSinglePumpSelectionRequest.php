<?php

namespace Modules\Selection\Http\Requests\SinglePump;

use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;

class ExportAtOnceSinglePumpSelectionRequest extends ExportAtOnceSelectionRequest
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
            'pumps_count' => ['required', 'numeric', 'max:9', 'min:1'],
            'reserve_pumps_count' => ['required', 'numeric', 'max:4', 'min:0'],
            'flow' => ['required', 'numeric'],
            'head' => ['required', 'numeric'],
            'fluid_temperature' => ['required', 'numeric']
        ]);
    }
}
