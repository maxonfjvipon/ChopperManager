<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Http\Requests\RqLogin;
use Redirect;
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
     * @throws ValidationException
     */
    public function __invoke(RqLogin $request): Responsable|Response
    {
        $request->authenticate();
        $request->session()->regenerate();
        return Redirect::route('projects.index');
    }
}
