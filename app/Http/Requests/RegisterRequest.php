<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'inn' =>  $this->inn
        ]);
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'inn' => 'nullable|max:12|unique:users',
            'phone' => 'required|max:12',
            'city_id' => 'required|exists:cities,id',
            'fio' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'business_id' => 'required|exists:businesses,id',
        ];
    }
}
