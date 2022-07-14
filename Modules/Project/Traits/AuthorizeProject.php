<?php

namespace Modules\Project\Traits;

use JetBrains\PhpStorm\Pure;
use Modules\Project\Entities\Project;
use Modules\User\Entities\User;

trait AuthorizeProject
{
    private function authorizeProjectForUser(User $user, Project $project): bool
    {
        return $user->can($this->authorizeProjectPermission($project));
    }

    /**
     * @param Project $project
     *
     * @return string
     */
    #[Pure]
 private function authorizeProjectPermission(Project $project): string
 {
     return $this->authorizeProjectByIdPermission($project->id);
 }

    private function authorizeProjectByIdPermission(string $id): string
    {
        return 'project_access_'.$id;
    }
}
