<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'itn' =>  $this->itn,
            'last_name' =>  $this->last_name
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
            'organization_name' => 'required|string|max:255',
            'itn' => 'nullable|max:12|unique:users',
            'phone' => 'required|max:12',
            'city_id' => 'required|exists:cities,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'business_id' => 'required|exists:businesses,id',
        ];
    }
}
