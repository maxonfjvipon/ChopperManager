<?php

namespace Modules\Auth\Http\Endpoints;

use App\Takes\TkRedirectToRoute;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Logout endpoint.
 * @package Modules\Auth\Takes
 */
final class EpLogout extends Controller
{
    /**
     * @param Request $request
     * @return Responsable|Response
     */
    public function __invoke(Request $request): Responsable|Response
    {
        return (new TkWithCallback(
            function () use ($request) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            },
            new TkRedirectToRoute('login')
        ))->act($request);
    }
}
