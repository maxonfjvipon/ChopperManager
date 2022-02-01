<?php

namespace Modules\Auth\Endpoints;

use App\Takes\TkRedirectedRoute;
use App\Http\Controllers\Controller;
use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Auth\Takes\TkRegisteredUser;
use Modules\Auth\Http\Requests\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Register attempt endpoint.
 * @package Modules\Auth\Takes
 */
final class RegisterAttemptEndpoint extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return Responsable|Response
     */
    public function __invoke(RegisterRequest $request): Responsable|Response
    {
        return TkRegisteredUser::new(
            TkRedirectedRoute::new('verification.notice')
        )->act($request);
    }
}
