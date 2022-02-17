<?php

namespace Modules\Selection\Http\Requests\DoublePump;

use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;

final class ExportAtOnceDoublePumpSelectionRequest extends ExportAtOnceSelectionRequest
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
            'dp_work_scheme_id' => ['sometimes', 'nullable', 'exists:tenant.double_pump_work_schemes,id'],
            'flow' => ['sometimes', 'nullable', 'numeric'],
            'head' => ['sometimes', 'nullable', 'numeric'],
            'fluid_temperature' => ['required', 'numeric']
        ]);
    }
}
