<?php

namespace Modules\Pump\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $pump
 * @property bool   $need_curves
 * @property bool   $need_info
 */
final class RqShowPump extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'need_curves' => ['required', 'boolean'],
            'need_info' => ['required', 'boolean'],
        ];
    }
}
