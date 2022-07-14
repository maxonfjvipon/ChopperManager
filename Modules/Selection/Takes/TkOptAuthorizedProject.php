<?php

namespace Modules\Selection\Takes;

use App\Interfaces\Take;
use App\Takes\TkTernary;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Project\Takes\TkAuthorizeProject;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that authorize project if {@project_id} is not -1.
 */
final class TkOptAuthorizedProject implements Take
{
    private string $project_id;

    private Take $origin;

    /**
     * Ctor.
     */
    public function __construct(string $project_id, Take $take)
    {
        $this->project_id = $project_id;
        $this->origin = $take;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function act(Request $request = null): Responsable|Response
    {
        return (new TkTernary(
            '-1' !== $this->project_id,
            new TkAuthorizeProject($this->project_id, $this->origin),
            $this->origin
        ))->act($request);
    }
}
