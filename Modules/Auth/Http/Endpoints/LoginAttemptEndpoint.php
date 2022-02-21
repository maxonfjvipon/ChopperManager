<?php

namespace Modules\Auth\Http\Endpoints;

use App\Takes\TkRedirectedRoute;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Auth\Http\Requests\LoginRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Login attempt endpoint
 * @package Modules\Auth\Takes
 */
final class LoginAttemptEndpoint extends Controller
{
    use AuthenticatesUsers;

    /**
     * @param LoginRequest $request
     * @return Responsable|Response
     */
    public function __invoke(LoginRequest $request): Responsable|Response
    {
        return TkWithCallback::new(
            function () use ($request) {
                $request->authenticate();
                $request->session()->regenerate();
            },
            TkRedirectedRoute::new('projects.index')
        )->act($request);

    }
}
