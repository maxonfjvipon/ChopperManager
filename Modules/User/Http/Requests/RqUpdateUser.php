<?php

namespace Modules\User\Http\Requests;

use Exception;
use Illuminate\Validation\Rules;

/**
 * Update user request.
 *
 * @property string $user
 */
final class RqUpdateUser extends RqStoreUser
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @throws Exception
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'itn' => ['sometimes', 'nullable', 'max:12', 'unique:users,itn,'.$this->user],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$this->user],
                'password' => ['sometimes', 'nullable', 'required_with_all:password_confirmation', 'confirmed', Rules\Password::default()],
                'password_confirmation' => ['sometimes', 'nullable', 'required_with_all:password', Rules\Password::default()],
            ]
        );
    }
}
