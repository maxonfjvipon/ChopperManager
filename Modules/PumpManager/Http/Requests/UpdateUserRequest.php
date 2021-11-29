<?php

namespace Modules\PumpManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Http\Requests\UserUpdatable;

class UpdateUserRequest extends FormRequest implements UserUpdatable
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'available_selection_type_ids' => ['array'],
            'available_series_ids' => ['array'],
        ];
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
