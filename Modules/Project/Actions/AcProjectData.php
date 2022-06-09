<?php

namespace Modules\Project\Actions;

use Exception;
use Modules\Project\Entities\ProjectStatus;
use Modules\User\Entities\Area;
use Modules\User\Entities\User;

final class AcProjectData
{
    /**
     * @return array
     * @throws Exception
     */
    public function __invoke(): array
    {
        return [
            'areas' => Area::allOrCached(),
            'statuses' => ProjectStatus::asArrayForSelect(),
            'users' => User::all()->map(fn(User $user) => [
                'id' => $user->id,
                'name' => $user->full_name
            ])
        ];
    }
}
