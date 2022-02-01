<?php

namespace Modules\Selection\Takes;

use App\Support\Take;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Takes\TkAuthorizedProject;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that authorize project if {@project_id} is not -1
 * @package Modules\Selection\Support
 */
final class TkOptAuthorizedProject implements Take
{
    /**
     * @var string $project_id
     */
    private string $project_id;

    /**
     * @var Take $origin
     */
    private Take $origin;

    /**
     * Ctor wrap.
     * @param string $project_id
     * @param Take $take
     * @return TkOptAuthorizedProject
     */
    public static function new(string $project_id, Take $take)
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
     * @throws AuthorizationException
     */
    public function act(Request $request = null): Responsable|Response
    {
        if ($this->project_id !== "-1") {
            return TkAuthorizedProject::byId(
                $this->project_id,
                $this->origin
            )->act($request);
        }
        return $this->origin->act($request);
    }
}
