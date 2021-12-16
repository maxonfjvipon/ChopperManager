<?php


namespace Modules\PumpManager\Http\Requests;

use Modules\User\Http\Requests\UpdateProfileRequest;

class PMUpdateProfileRequest extends UpdateProfileRequest
{

    public function rules(): array
    {
        return [
            'organization_name' => 'required|string|max:255',
            'inn' => 'max:12|unique:tenant.users,inn,' . $this->user()->id,
            'phone' => 'required|max:12',
            'country_id' => 'required|exists:tenant.countries,id',
            'city' => 'nullable|string|max:255',
            'currency_id' => 'required|exists:tenant.currencies,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:tenant.users,email,' . $this->user()->id,
            'business_id' => 'required|exists:tenant.businesses,id',
        ];
    }
}
