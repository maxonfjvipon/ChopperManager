<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use App\Takes\TkWithCallback;

/**
 * Login endpoint.
 * @package Modules\Auth\Takes
 */
final class EpLogin extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkWithCallback(
                function () {
                    if (!session()->has('url.intended')) {
                        session(['url.intended' => url()->previous()]);
                    }
                },
                new TkInertia('Auth::Login')
            )
        );
    }
}
