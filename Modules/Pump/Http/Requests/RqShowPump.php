<?php

namespace Modules\Pump\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RqShowPump extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'need_curves' => ['required', 'boolean']
        ];
    }
}
