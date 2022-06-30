<?php

namespace Modules\User\Http\Requests;

use App\Rules\ArrayExistsInArray;
use BenSampo\Enum\Rules\EnumValue;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\In;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\User\Entities\Area;
use Modules\User\Entities\UserRole;

/**
 * Store user request.
 *
 * @property string|null $organization_name
 * @property string|null $itn
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $first_name
 * @property string $middle_name
 * @property string|null $last_name
 * @property int $area_id
 * @property boolean $is_active
 * @property array $available_series_ids
 * @property int $role
 */
class RqStoreUser extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws Exception
     */
    public function rules(): array
    {
        return [
            'organization_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'itn' => ['sometimes', 'nullable', 'max:12', 'unique:users,itn'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['sometimes', 'nullable', 'required', 'max:12'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'role' => ['required', new EnumValue(UserRole::class)],
            'area_id' => ['required', new In(Area::allOrCached()->pluck('id')->all())],
            'is_active' => ['required', 'boolean'],
            'available_series_ids' => ['sometimes', 'nullable', 'array', new ArrayExistsInArray(PumpSeries::pluck('id')->all())],
        ];
    }

    public function userProps(): array
    {
        return array_merge(
            [
                'organization_name' => $this->organization_name,
                'itn' => $this->itn,
                'email' => $this->email,
                'phone' => $this->phone,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'area_id' => $this->area_id,
                'role' => $this->role,
                'is_active' => $this->is_active,
            ], !!$this->password
                ? ['password' => Hash::make($this->password)]
                : []
        );
    }
}
