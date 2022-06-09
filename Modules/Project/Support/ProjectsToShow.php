<?php

namespace Modules\Project\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\User\Entities\User;

/**
 * Projects to show
 */
final class ProjectsToShow implements Arrayable
{
    /**
     * @param User|Authenticatable $user
     */
    public function __construct(private User|Authenticatable $user)
    {
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return [
            'projects' => $this->user->projects()
                ->withCount('selections')
                ->with(['selections' => function ($query) {
                    $query->select('id', 'project_id', 'selected_pump_name', 'flow', 'head');
                }])
                ->get(['name'])
                ->all()
        ];
    }
}
