<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\Auth\TkRegisterUser;
use App\Takes\TkRedirectToRoute;
use Modules\Auth\Http\Requests\RqRegister;

/**
 * Register attempt endpoint.
 */
final class EpRegisterAttempt extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqRegister $request
     */
    public function __construct(RqRegister $request)
    {
        parent::__construct(
            new TkRegisterUser(
                new TkRedirectToRoute('projects.index'),
            ),
            $request
        );
    }
}
