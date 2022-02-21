<?php

namespace Modules\User\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\User\Entities\User;

/**
 * User to edit.
 */
final class UserToEdit implements Arrayable
{
    /**
     * @var User $user
     */
    private User $user;

    /**
     * Ctor wrap.
     * @param User $user
     * @return UserToEdit
     */
    public static function new(User $user): UserToEdit
    {
        return new self($user);
    }

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return [
            'user' => [
                'data' => [
                    'id' => $this->user->id,
                    'organization_name' => $this->user->organization_name,
                    'itn' => $this->user->itn,
                    'phone' => $this->user->phone,
                    'country_id' => $this->user->country_id,
                    'city' => $this->user->city,
                    'postcode' => $this->user->postcode,
                    'first_name' => $this->user->first_name,
                    'middle_name' => $this->user->middle_name,
                    'last_name' => $this->user->last_name,
                    'email' => $this->user->email,
                    'business_id' => $this->user->business_id,
                    'is_active' => $this->user->is_active,
                    'available_series_ids' => $this->user->available_series()->pluck('id')->all(),
                    'available_selection_type_ids' => $this->user->available_selection_types()->pluck('id')->all()
                ]
            ],
            'filter_data' => UsersFilterData::new()->asArray()
        ];
    }
}
