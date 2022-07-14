<?php

namespace Modules\User\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;
use Modules\User\Entities\Area;

/**
 * Update profile request.
 */
final class RqUpdateProfile extends FormRequest
{
    /**
     * @throws Exception
     */
    public function rules(): array
    {
        return [
            'organization_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'itn' => ['sometimes', 'nullable', 'max:12', 'unique:users,itn,'.$this->user()->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$this->user()->id],
            'phone' => ['sometimes', 'nullable', 'max:12'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'area_id' => ['required', new In(Area::allOrCached()->pluck('id')->all())],
        ];
    }
}
