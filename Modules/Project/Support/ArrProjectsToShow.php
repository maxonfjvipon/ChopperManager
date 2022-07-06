<?php

namespace Modules\Project\Support;

use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Modules\Project\Entities\Project;
use Modules\User\Entities\User;

/**
 * Projects to show
 */
final class ArrProjectsToShow extends ArrEnvelope
{
    /**
     * Ctor.
     * @param User|null $user
     */
    public function __construct(?User $user = null)
    {
        parent::__construct(
            new ArrMapped(
                (!!$user
                    ? $user->projects()->with('area')
                    : ((Auth::user()->isAdmin()
                        ? Project::with('area')
                        : Auth::user()->projects()->with('area'))
                    )->withPumpStations())
                    ->withAllParticipants()
                    ->get()
                    ->all(),
                fn(Project $project) => $project->asArray()
            )
        );
    }
}
