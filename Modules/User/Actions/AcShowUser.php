<?php

namespace Modules\User\Actions;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Modules\Project\Support\ArrProjectsFilterData;
use Modules\Project\Support\ArrProjectsToShow;
use Modules\User\Entities\User;
use Modules\User\Transformers\RcUserToShow;

/**
 * Show user action.
 */
final class AcShowUser extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(private User $user)
    {
        parent::__construct(
            new ArrMerged(
                ['user' => new RcUserToShow($this->user)],
                new ArrObject(
                    'projects',
                    $projects = new ArrSticky(
                        new ArrProjectsToShow($this->user)
                    )
                ),
                new ArrProjectsFilterData($projects)
            )
        );
    }
}
