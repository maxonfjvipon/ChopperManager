<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest implements UserRegisterable
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
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:tenant.users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function userProps(): array
    {
        return [
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ];
    }
}
