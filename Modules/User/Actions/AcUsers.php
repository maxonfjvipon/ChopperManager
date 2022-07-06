<?php

namespace Modules\User\Actions;

use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\User\Entities\User;
use Modules\User\Entities\UserRole;

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
                        'areas' => array_values(
                            ($users = User::with(['area', 'dealer'])
                                ->get())
                                ->map(fn(User $user) => $user->area->name)
                                ->unique()
                                ->all()
                        ),
                        'roles' => UserRole::getDescriptions(),
                    ])
                ),
                new ArrObject(
                    "users",
                    new ArrMapped(
                        $users->all(),
                        fn(User $user) => [
                            'id' => $user->id,
                            'created_at' => formatted_date($user->created_at),
                            'dealer' => $user->dealer->name,
                            'full_name' => $user->full_name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'area' => $user->area->name,
                            'is_active' => $user->is_active,
                            'role' => $user->role->description,
                        ]
                    )
                )
            )
        );
    }
}
