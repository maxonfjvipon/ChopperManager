<?php

namespace Modules\User\Actions;

use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\User\Entities\User;

/**
 * Users action.
 */
final class AcUsers extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    "filter_data",
                    new ArrForFiltering([
                        'areas' => ($users = User::with('area')
                            ->get())
                            ->map(fn(User $user) => $user->area->name)
                            ->unique()
                            ->all()
                    ])
                ),
                new ArrObject(
                    "users",
                    new ArrMapped(
                        $users->all(),
                        fn(User $user) => [
                            'id' => $user->id,
                            'created_at' => formatted_date($user->created_at),
                            'organization_name' => $user->organization_name,
                            'full_name' => $user->full_name,
                            'phone' => $user->phone,
                            'email' => $user->email,
                            'area' => $user->area->name,
                            'is_active' => $user->is_active,
                        ]
                    )
                )
            )
        );
    }
}
