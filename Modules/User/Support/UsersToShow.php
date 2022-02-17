<?php

namespace Modules\User\Support;

use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\PumpManager\Entities\PMUser;
use Modules\User\Entities\Business;

/**
 * Users to show.
 */
final class UsersToShow implements Arrayable
{
    /**
     * Ctor wrap.
     * @return UsersToShow
     */
    public static function new(): UsersToShow
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return ArrMerged::new(
            ArrObject::new(
                "filter_data",
                ArrForFiltering::new(
                    ['businesses' => Business::allOrCached()->pluck('name')->all()]
                )
            ),
            ArrObject::new(
                "users",
                ArrMapped::new(
                    PMUser::with(['country' => function ($query) {
                        $query->select('id', 'name');
                    }, 'business'])
                        ->withCount('projects')
                        ->get(['id', 'organization_name', 'business_id', 'created_at',
                            'country_id', 'city', 'first_name', 'middle_name', 'last_name', 'is_active', 'phone', 'email'
                        ])->all(),
                    fn(PMUser $user) => [
                        'id' => $user->id,
                        'key' => $user->id,
                        'created_at' => date_format($user->created_at, 'd.m.Y'),
                        'organization_name' => $user->organization_name,
                        'full_name' => $user->full_name,
                        'phone' => $user->phone,
                        'email' => $user->email,
                        'business' => $user->business->name,
                        'country' => $user->country->name,
                        'city' => $user->city,
                        'is_active' => $user->is_active
                    ]
                )
            )
        )->asArray();
    }
}
