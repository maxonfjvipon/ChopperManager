<?php

namespace Modules\Selection\Takes;

use App\Takes\Take;
use App\Takes\TkTernary;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Project\Takes\TkAuthorizeProject;
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
     * @throws Exception
     */
    public function act(Request $request = null): Responsable|Response
    {
        return (new TkTernary(
            $this->project_id !== "-1",
            new TkAuthorizeProject($this->project_id, $this->origin),
            $this->origin
        ))->act($request);
    }
}
