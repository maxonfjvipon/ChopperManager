<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_name' => 'required|string|max:255',
            'itn' => 'max:12|unique:users,itn,' . $this->user()->id,
            'phone' => 'required|max:12',
            'country_id' => 'required|exists:countries,id',
            'city' => 'nullable|string|max:255',
            'currency_id' => 'required|exists:currencies,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user()->id,
            'business_id' => 'required|exists:businesses,id',
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
