<?php

namespace Modules\Pump\Http\Requests;

class PumpBrandUpdateRequest extends PumpBrandStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:pump_brands,name,' . $this->pump_brand->id]
        ];
    }
}
