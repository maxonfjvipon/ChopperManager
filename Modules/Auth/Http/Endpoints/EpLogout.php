<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Logout endpoint.
 */
final class EpLogout extends TakeEndpoint
{
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkWithCallback(
                function () use ($request) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                },
                new TkRedirectToRoute('login')
            ),
            $request
        );
    }
}
