<?php

namespace Modules\Project\Actions;

use App\Support\ArrForFiltering;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrTernary;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrUnique;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrValues;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectStatus;

/**
 * Projects action.
 */
final class AcProjects
{
    /**
     * @throws Exception
     */
    public function __invoke(): Arrayable
    {
        return new ArrMerged(
            new ArrObject(
                'projects',
                $projects = new ArrSticky(
                    new ArrTernary(
                        Auth::user()->isAdmin(),
                        new ArrMapped(
                            new ArrFromCallback(
                                fn() => Project::with('area')
                                    ->withAllPartners()
                                    ->get()
                                    ->all()
                            ),
                            fn(Project $project) => $project->asArrayForAdmin()
                        ),
                        new ArrMapped(
                            new ArrFromCallback(fn() => Auth::user()->projects()->with('area')->get()->all()),
                            fn(Project $project) => $project->asArrayforClient()
                        )
                    )
                ),
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
                ]),
            ),
        );
    }
}
