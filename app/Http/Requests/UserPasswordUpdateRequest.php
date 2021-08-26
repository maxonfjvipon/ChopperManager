<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UserPasswordUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required_with_all:password,password_confirmation|current_password',
            'password' => ['required_with_all:current_password,password_confirmation', 'confirmed', Rules\Password::default()],
            'password_confirmation' => ['required_with_all:password,current_password', Rules\Password::default()],
        ];
    }
}
