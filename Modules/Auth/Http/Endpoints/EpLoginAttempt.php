<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Auth\Http\Requests\RqLogin;
use Symfony\Component\HttpFoundation\Response;

/**
 * Login attempt endpoint
 * @package Modules\Auth\Takes
 */
final class EpLoginAttempt extends Controller
{
    use AuthenticatesUsers;

    /**
     * @param RqLogin $request
     * @return Responsable|Response
     */
    public function __invoke(RqLogin $request): Responsable|Response
    {
        return (new TkWithCallback(
            function () use ($request) {
                $request->authenticate();
                $request->session()->regenerate();
            },
            new TkRedirectToRoute('projects.index')
        ))->act($request);

    }
}
