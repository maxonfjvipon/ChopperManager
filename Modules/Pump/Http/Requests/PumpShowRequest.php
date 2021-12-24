<?php

namespace Modules\Pump\Http\Requests;

class PumpShowRequest extends PumpableRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'need_curves' => ['required', 'boolean']
        ]);
    }
}
