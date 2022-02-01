<?php

namespace Modules\Auth\Endpoints;

use App\Takes\TkRedirectedRoute;
use App\Http\Controllers\Controller;
use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\AdminPanel\Entities\Tenant;
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
     * @throws ValidationException
     */
    public function __invoke(LoginRequest $request): Responsable|Response
    {
        $request->authenticate(Tenant::current()->guard);
        $request->session()->regenerate();
        return TkRedirectedRoute::new('projects.index')->act();
    }
}
