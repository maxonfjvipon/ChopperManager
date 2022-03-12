<?php

namespace Modules\User\Support;

use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\User\Entities\User;
use Modules\User\Entities\Business;

/**
 * Users to show.
 */
final class UsersToShow implements Arrayable
{
    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return (new ArrMerged(
            new ArrObject(
                "filter_data",
                new ArrForFiltering(
                    ['businesses' => Business::allOrCached()->pluck('name')->all()]
                )
            ),
            new ArrObject(
                "users",
                new ArrMapped(
                    User::with(['country' => function ($query) {
                        $query->select('id', 'name');
                    }, 'business'])
                        ->withCount('projects')
                        ->get(['id', 'organization_name', 'business_id', 'created_at',
                            'country_id', 'city', 'first_name', 'middle_name', 'last_name', 'is_active', 'phone', 'email'
                        ])->all(),
                    fn(User $user) => [
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
        ))->asArray();
    }
}
