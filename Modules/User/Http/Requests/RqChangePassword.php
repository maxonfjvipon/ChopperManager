<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Change password request.
 *
 * @property string $password
 */
final class RqChangePassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'current_password' => 'nullable|required_with_all:password,password_confirmation|current_password',
            'password' => ['nullable', 'required_with_all:current_password,password_confirmation', 'confirmed', Password::default()],
            'password_confirmation' => ['nullable', 'required_with_all:password,current_password', Password::default()],
        ];
    }
}
