<?php

namespace Modules\Project\Actions;

use Auth;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectStatus;
use Modules\Project\Transformers\RcProjectToEdit;
use Modules\User\Entities\Area;
use Modules\User\Entities\Contractor;
use Modules\User\Entities\User;

/**
 * Edit or create project action.
 */
final class AcEditOrCreateProject extends ArrEnvelope
{
    /**
     * Ctor.
     * @param Project|null $project
     * @throws Exception
     */
    public function __construct(?Project $project = null)
    {
        parent::__construct(
            new ArrMerged(
                [
                    'areas' => Area::allOrCached(),
                    'statuses' => ProjectStatus::asArrayForSelect(),
                    'contractors' => Auth::user()
                        ->contractors
                        ->map(fn(Contractor $contractor) => [
                            'id' => $contractor->id,
                            'name' => $contractor->name
                        ])
                ],
                new ArrIf(
                    Auth::user()->isAdmin(),
                    fn() => [
                        'users' => User::all()->map(fn(User $user) => [
                            'id' => $user->id,
                            'name' => $user->full_name
                        ])
                    ]
                ),
                new ArrIf(
                    !!$project,
                    fn() => ['project' => new RcProjectToEdit($project)]
                )
            )
        );
    }
}
