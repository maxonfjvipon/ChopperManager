<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkFromCallback;
use App\Takes\TkInertia;
use App\Takes\TkWithCallback;

/**
 * Login endpoint.
 */
final class EpLogin extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkFromCallback(
                fn () => new TkWithCallback(
                    function () {
                        if (!session()->has('url.intended')) {
                            session(['url.intended' => url()->previous()]);
                        }
                    },
                    new TkInertia('Auth::Login')
                )
            )
        );
    }
}
