<?php

namespace Modules\Pump\Http\Requests;


class LoadPumpsRequest extends PumpableRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'filter' => ['sometimes', 'nullable', 'string']
        ]);
    }
}
