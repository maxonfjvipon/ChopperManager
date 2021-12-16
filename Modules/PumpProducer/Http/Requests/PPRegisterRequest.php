<?php

namespace Modules\PumpProducer\Http\Requests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\User\Entities\Country;

class PPRegisterRequest extends RegisterRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|unique:tenant.users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'country_id' => 'required|exists:tenant.countries,id',
        ];
    }

    public function userProps(): array
    {
        return [
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'country_id' => $this->country_id,
            'currency_id' => Country::find($this->country_id)->first()->currency_id,
        ];
    }
}
