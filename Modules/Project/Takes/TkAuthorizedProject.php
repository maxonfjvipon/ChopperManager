<?php

namespace Modules\Project\Takes;

use App\Takes\TkAuthorized;
use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Project\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that checks for user access for specified project.
 * @package App\Takes
 */
final class TkAuthorizedProject implements Take
{
    /**
     * @var string $project_id
     */
    private string $project_id;

    /**
     * @var Take
     */
    private Take $origin;

    /**
     * Ctor wrap.
     * @param Project $project
     * @param Take $take
     * @return TkAuthorizedProject
     */
    public static function byProject(Project $project, Take $take): TkAuthorizedProject
    {
        return TkAuthorizedProject::byId($project->id, $take);
    }

    /**
     * @param string $project_id
     * @param Take $take
     * @return TkAuthorizedProject
     */
    public static function byId(string $project_id, Take $take): TkAuthorizedProject
    {
        return new self($project_id, $take);
    }

    /**
     * Ctor.
     * @param string $project_id
     * @param Take $take
     */
    public function __construct(string $project_id, Take $take)
    {
        $this->project_id = $project_id;
        $this->origin = $take;
    }

    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        return TkAuthorized::new(
            'project_access_' . $this->project_id,
            $this->origin
        )->act($request);
    }
}
