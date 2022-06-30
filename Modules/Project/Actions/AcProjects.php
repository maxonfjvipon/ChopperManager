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
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrTernary;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrUnique;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectStatus;

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
            new ArrFromCallback(
                fn() => new ArrMerged(
                    new ArrObject(
                        'projects',
                        $projects = new ArrSticky(
                            new ArrMapped(
                                Project::with('area')
                                    ->withAllContractors()
                                    ->withPumpStations()
                                    ->get()
                                    ->all(),
                                fn(Project $project) => $project->asArray()
                            ),
                        )
                    ),
                    new ArrObject(
                        'filter_data',
                        new ArrForFiltering([
                            'areas' => new ArrUnique(
                                new ArrMapped(
                                    $projects,
                                    fn($project) => $project['area']
                                )
                            ),
                            'statuses' => ProjectStatus::getDescriptions()
                        ])
                    )
                )
            )
        );
    }
}
