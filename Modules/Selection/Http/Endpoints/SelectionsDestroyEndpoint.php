<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedBack;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Selection\Entities\Selection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections destroy endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class SelectionsDestroyEndpoint extends Controller
{
    /**
     * @param Selection $selection
     * @return Responsable|Response
     */
    public function __invoke(Selection $selection): Responsable|Response
    {
        return TkAuthorizedProject::byId(
            $selection->project_id,
            TkAuthorized::new(
                'selection_delete',
                TkWithCallback::new(
                    fn() => $selection->delete(),
                    TkRedirectedBack::new()
                )
            )
        )->act();
    }
}
