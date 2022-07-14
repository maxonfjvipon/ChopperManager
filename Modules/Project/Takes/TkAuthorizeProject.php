<?php

namespace Modules\Project\Takes;

use App\Interfaces\Take;
use App\Takes\TkAuthorize;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that checks for user access for specified project.
 */
final class TkAuthorizeProject implements Take
{
    /**
     * Ctor.
     */
    public function __construct(private string $project_id, private Take $origin)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function act(Request $request = null): Responsable|Response
    {
        return (new TkAuthorize(
            'project_access_'.$this->project_id,
            $this->origin
        ))->act($request);
    }
}
