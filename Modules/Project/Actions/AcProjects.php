<?php

namespace Modules\Project\Actions;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Modules\Project\Support\ArrProjectsFilterData;
use Modules\Project\Support\ArrProjectsToShow;

/**
 * Projects action.
 */
final class AcProjects extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'projects',
                    $projects = new ArrSticky(
                        new ArrProjectsToShow()
                    )
                ),
                new ArrProjectsFilterData($projects)
            )
        );
    }
}
