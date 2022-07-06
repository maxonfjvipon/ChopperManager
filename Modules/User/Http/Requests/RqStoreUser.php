<?php

namespace Modules\User\Http\Requests;

use BenSampo\Enum\Rules\EnumValue;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\In;
use Modules\User\Entities\Area;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\User\Entities\UserRole;

/**
 * Store user request.
 *
 * @property string|null $itn
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $first_name
 * @property string $middle_name
 * @property string|null $last_name
 * @property int $area_id
 * @property boolean $is_active
 * @property int $role
 * @property int $dealer_id
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
            'dealer_id' => ['required', new In(Dealer::allOrCached()->pluck('id')->all())],
            'itn' => ['sometimes', 'nullable', 'max:12', 'unique:users,itn'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['sometimes', 'nullable', 'max:12'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'role' => ['required', new EnumValue(UserRole::class)],
            'area_id' => ['required', new In(Area::allOrCached()->pluck('id')->all())],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function userProps(): array
    {
        return array_merge(
            [
                'dealer_id' => $this->dealer_id,
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
