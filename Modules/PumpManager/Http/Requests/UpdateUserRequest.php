<?php

namespace Modules\PumpManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\PumpManager\Entities\User;
use Modules\User\Entities\Country;
use Modules\User\Http\Requests\Interfaces\UserUpdatable;

class UpdateUserRequest extends FormRequest implements UserUpdatable
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'organization_name' => 'required|string|max:255',
            'itn' => 'sometimes|nullable|max:12|unique:tenant.users,itn,' . $this->user,
            'email' => 'required|string|email|max:255|unique:tenant.users,email,' . $this->user,
            'phone' => 'required|max:12',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postcode' => 'sometimes|nullable|string|max:255',
            'country_id' => 'required|exists:tenant.countries,id',
            'business_id' => 'required|exists:tenant.businesses,id',
            'is_active' => 'required|boolean',
            'available_selection_type_ids' => ['array', 'nullable'],
            'available_series_ids' => ['array', 'nullable'],
        ];
    }

    public function userProps(): array
    {
        return [
            'organization_name' => $this->organization_name,
            'itn' => $this->itn,
            'email' => $this->email,
            'phone' => $this->phone,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'city' => $this->city,
            'postcode' => $this->postcode,
            'country_id' => $this->country_id,
            'currency_id' => Country::find($this->country_id)->first()->currency_id,
            'business_id' => $this->business_id,
            'is_active' => $this->is_active,
        ];
    }

    public function availableProps(): array
    {
        return [
            'available_selection_type_ids' => $this->available_selection_type_ids ?? [],
            'available_series_ids' => $this->available_series_ids ?? [],
        ];
    }
}
