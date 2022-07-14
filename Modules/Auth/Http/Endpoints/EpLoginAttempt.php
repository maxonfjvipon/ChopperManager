<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkWithCallback;
use Modules\Auth\Http\Requests\RqLogin;
use Modules\Project\Takes\TkRedirectToProjectsIndex;

/**
 * Login attempt endpoint.
 */
final class EpLoginAttempt extends TakeEndpoint
{
    public function __construct(RqLogin $request)
    {
        parent::__construct(
            new TkWithCallback(
                function () use ($request) {
                    $request->authenticate();
                    $request->session()->regenerate();
                },
                new TkRedirectToProjectsIndex()
            )
        );
    }
}
