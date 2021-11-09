<?php

namespace Modules\AdminPanel\Http\Requests;

class LoginRequest extends \Modules\Auth\Http\Requests\LoginRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'login' => 'required|string|max:255',
            'password' => 'required|string'
        ];
    }
}
