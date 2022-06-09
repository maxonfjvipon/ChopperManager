<?php

namespace Modules\Auth\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Modules\User\Contracts\ChangeUser\WithUserProps;
use Illuminate\Validation\Rules;

final class RqRegister extends FormRequest implements WithUserProps
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'organization_name' => 'required|string|max:255',
            'itn' => 'sometimes|nullable|max:12|unique:users',
            'phone' => 'required|max:12',
            'area_id' => 'required|exists:areas,id',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * @throws Exception
     */
    public function userProps(): array
    {
        return [
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'organization_name' => $this->organization_name,
            'itn' => $this->itn,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'area_id' => $this->area_id
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
