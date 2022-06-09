<?php

namespace Modules\Selection\Http\Requests\SinglePump;

use Modules\Selection\Http\Requests\RqPumpStationCurves;

final class RqSinglePumpSelectionCurves
{
    protected function prepareForValidation()
    {
        $this->merge([
            'reserve_pumps_count' => $this->pumps_count - $this->main_pumps_count
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
            'pump_id' => ['required', 'exists:pumps,id'],
            'pumpable_type' => ['required', 'string'],
            'pumps_count' => ['required', 'numeric'],
            'main_pumps_count' => ['required', 'numeric'],
            'reserve_pumps_count' => ['required', 'numeric'],
            'head' => ['sometimes', 'nullable', 'numeric'],
            'flow' => ['sometimes', 'nullable', 'numeric'],
        ];
    }
}
