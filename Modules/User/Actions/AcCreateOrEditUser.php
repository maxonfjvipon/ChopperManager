<?php

namespace Modules\User\Actions;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\User\Entities\Area;
use Modules\User\Entities\User;
use Modules\User\Entities\UserRole;
use Modules\User\Transformers\RcUserToEdit;

/**
 * Create or edit user action.
 */
final class AcCreateOrEditUser extends ArrEnvelope
{
    /**
     * @throws Exception
     */
    public function __construct(private ?User $user = null)
    {
        parent::__construct(
            new ArrMerged(
                [
                    'filter_data' => [
                        'areas' => Area::allOrCached(),
                        'roles' => UserRole::asArrayForSelect(),
                        'dealers' => Dealer::allOrCached()->map(fn (Dealer $dealer) => [
                            'id' => $dealer->id,
                            'name' => $dealer->name,
                        ]),
                    ],
                ],
                new ArrIf(
                    (bool) $this->user,
                    fn () => ['user' => new RcUserToEdit($this->user)]
                )
            )
        );
    }
}
