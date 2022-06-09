<?php

namespace Modules\Selection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

final class RqPumpStationCurves extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'pump_id' => ['required', 'exists:pumps,id'],
            'main_pumps_count' => ['required', 'numeric'],
            'reserve_pumps_count' => ['required', 'numeric'],
            'head' => ['sometimes', 'nullable', 'numeric'],
            'flow' => ['sometimes', 'nullable', 'numeric'],
        ];
    }
}
