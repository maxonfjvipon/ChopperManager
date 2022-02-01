<?php

namespace Modules\Auth\Endpoints;

use App\Takes\TkRedirectedRoute;
use App\Http\Controllers\Controller;
use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

/**
 * Logout endpoint.
 * @package Modules\Auth\Takes
 */
final class LogoutEndpoint extends Controller
{
    /**
     * @param Request $request
     * @return Responsable|Response
     */
    public function __invoke(Request $request): Responsable|Response
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return TkRedirectedRoute::new('login')->act($request);
    }
}
