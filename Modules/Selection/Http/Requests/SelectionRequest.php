<?php

namespace Modules\Selection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class SelectionRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'pump_series_ids' => implode(",", array_filter($this->pump_series_ids, fn($item) => gettype($item) === 'integer'))
        ]);
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
