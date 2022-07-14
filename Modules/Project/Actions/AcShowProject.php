<?php

namespace Modules\Project\Actions;

use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Project\Entities\Project;
use Modules\Project\Transformers\RcProjectToShow;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

final class AcShowProject extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(Project $project)
    {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'project',
                    new RcProjectToShow($project)
                ),
                new ArrObject(
                    'filter_data',
                    new ArrForFiltering([
                        'station_types' => StationType::getDescriptions(),
                        'selection_types' => SelectionType::getDescriptions(),
                    ])
                )
            )
        );
    }
}
