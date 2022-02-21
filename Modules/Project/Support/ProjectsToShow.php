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
     * @var Authenticatable|User $user
     */
    private User|Authenticatable $user;

    /**
     * @param User|Authenticatable $usr
     */
    public function __construct(User|Authenticatable $usr)
    {
        $this->user = $usr;
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
