<?php

namespace Modules\Project\Actions;

use App\Support\ArrForFiltering;
use Exception;
use JetBrains\PhpStorm\Pure;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Project\Entities\Project;
use Modules\Project\Transformers\RcProjectToShow;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

final class AcShowProject
{
    /**
     * @param Project $project
     * @return ArrMerged
     * @throws Exception
     */
    public function __invoke(Project $project): ArrMerged
    {
        return new ArrMerged(
            ['project' => new RcProjectToShow($project)],
            new ArrObject(
                "filter_data",
                new ArrForFiltering([
                    'station_types' => StationType::getDescriptions(),
                    'selection_types' => SelectionType::getDescriptions()
                ])
            )
        );
    }
}
