<?php

namespace Modules\Selection\Http\Requests\DoublePump;

use Modules\Selection\Http\Requests\RqExportAtOnceSelection;

final class RqExportDoublePumpSelectionAtOnce extends RqExportAtOnceSelection
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
            'pump_id' => ['required', 'exists:pumps,id'],
            'dp_work_scheme_id' => ['sometimes', 'nullable', 'exists:double_pump_work_schemes,id'],
            'flow' => ['sometimes', 'nullable', 'numeric'],
            'head' => ['sometimes', 'nullable', 'numeric'],
            'fluid_temperature' => ['required', 'numeric']
        ]);
    }
}
