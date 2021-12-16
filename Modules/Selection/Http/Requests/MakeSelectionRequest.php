<?php

namespace Modules\Selection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class MakeSelectionRequest extends FormRequest
{
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
