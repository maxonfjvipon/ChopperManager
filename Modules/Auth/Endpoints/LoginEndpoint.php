<?php

namespace Modules\Auth\Endpoints;

use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Login endpoint.
 * @package Modules\Auth\Takes
 */
final class LoginEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return TkWithCallback::new(
            function () {
                if (!session()->has('url.intended')) {
                    session(['url.intended' => url()->previous()]);
                }
            },
            TkInertia::new('Auth::Login')
        )->act();
    }
}
