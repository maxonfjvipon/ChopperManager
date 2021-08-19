<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UserUpdateRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'inn' => 'max:12|unique:users',
            'phone' => 'required|max:12',
            'city_id' => 'required|exists:cities,id',
            'fio' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'current_password' => 'required_with_all:password,password_confirmation|current_password',
            'password' => ['required_with_all:current_password,password_confirmation', 'confirmed', Rules\Password::default()],
            'password_confirmation' => ['required_with_all:password,current_password', Rules\Password::default()],
            'business_id' => 'required|exists:businesses,id',
            'role_id' => 'required|exists:roles,id'
        ];
    }
}
