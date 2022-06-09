<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\Auth\TkRegisterUser;
use App\Takes\TkRedirectToRoute;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Auth\Http\Requests\RqRegister;
use Symfony\Component\HttpFoundation\Response;

final class EpRegisterAttempt extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param RqRegister $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(RqRegister $request): Responsable|Response
    {
        return (new TkRegisterUser(
            new TkRedirectToRoute('projects.index')
        ))->act($request);
    }
}
