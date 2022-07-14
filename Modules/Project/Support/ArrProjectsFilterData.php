<?php

namespace Modules\Project\Support;

use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrUnique;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectStatus;

/**
 * Projects filter data.
 */
final class ArrProjectsFilterData extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(Arrayable $projects)
    {
        parent::__construct(
            new ArrObject(
                'filter_data',
                new ArrForFiltering([
                    'areas' => new ArrUnique(
                        new ArrMapped(
                            $projects,
                            fn (array|Project $project) => $project['area']->name ?? $project['area'] ?? $project->area->name
                        )
                    ),
                    'statuses' => ProjectStatus::getDescriptions(),
                ])
            )
        );
    }
}
