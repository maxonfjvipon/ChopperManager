<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkRedirectBack;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizeProject;
use Modules\Selection\Entities\Selection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Destroy selection endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class EpDestroySelection extends Controller
{
    /**
     * @param Selection $selection
     * @return Responsable|Response
     */
    public function __invoke(Selection $selection): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => $selection->delete(),
            new TkRedirectBack()
        ))->act();
    }
}
