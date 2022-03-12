<?php

namespace Modules\Auth\Http\Endpoints;

use App\Takes\TkRedirectedRoute;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Modules\Auth\Takes\TkRegisteredUser;
use Modules\Auth\Http\Requests\RegisterUserRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Register attempt endpoint.
 * @package Modules\Auth\Takes
 */
final class RegisterAttemptEndpoint extends Controller
{
    /**
     * @param RegisterUserRequest $request
     * @return Responsable|Response
     * @throws \Exception
     */
    public function __invoke(RegisterUserRequest $request): Responsable|Response
    {
        return (new TkRegisteredUser(
            new TkRedirectedRoute('verification.notice')
        ))->act($request);
    }
}
