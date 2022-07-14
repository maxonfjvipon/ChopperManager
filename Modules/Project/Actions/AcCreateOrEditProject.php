<?php

namespace Modules\Project\Actions;

use Auth;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectStatus;
use Modules\Project\Transformers\RcProjectToEdit;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\User\Entities\Area;

/**
 * Edit or create project action.
 */
final class AcCreateOrEditProject extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(?Project $project = null)
    {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'filter_data',
                    new ArrMerged(
                        [
                            'areas' => Area::allOrCached(),
                            'statuses' => ProjectStatus::asArrayForSelect(),
                        ],
                        new ArrIf(
                            Auth::user()->isAdmin(),
                            fn () => ['dealers' => Dealer::allOrCached()->only(['id', 'name'])]
                        ),
                    )
                ),
                new ArrIf(
                    (bool) $project,
                    fn () => ['project' => new RcProjectToEdit($project)]
                )
            )
        );
    }
}
