<?php

namespace Modules\User\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\User\Entities\User;

/**
 * User profile.
 */
final class UserProfile implements Arrayable
{
    /**
     * @var Authenticatable|User $user
     */
    private Authenticatable|User $user;

    /**
     * Ctor.
     * @param Authenticatable|User $user
     */
    public function __construct(Authenticatable|User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return [
            'data' => [
                'id' => $this->user->id,
                'organization_name' => $this->user->organization_name,
                'itn' => $this->user->itn,
                'phone' => $this->user->phone,
                'country_id' => $this->user->country_id,
                'currency_id' => $this->user->currency_id,
                'city' => $this->user->city,
                'postcode' => $this->user->postcode,
                'first_name' => $this->user->first_name,
                'middle_name' => $this->user->middle_name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
                'business_id' => $this->user->business_id,
            ]
        ];
    }
}
