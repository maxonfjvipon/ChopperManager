<?php

namespace Modules\Project\Actions;

use App\Support\ArrForFiltering;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrUnique;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectStatus;
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
