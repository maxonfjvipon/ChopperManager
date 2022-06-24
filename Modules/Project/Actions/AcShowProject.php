<?php

namespace Modules\Project\Actions;

use App\Interfaces\RsAction;
use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Modules\Project\Entities\Project;
use Modules\Project\Transformers\RcProjectToShow;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

final class AcShowProject extends ArrEnvelope
{
    /**
     * Ctor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        parent::__construct(
            new ArrayableOf([
                'project' => new RcProjectToShow($project),
                'filter_data' => new ArrForFiltering([
                    'station_types' => StationType::getDescriptions(),
                    'selection_types' => SelectionType::getDescriptions()
                ])
            ])
        );
    }
}
