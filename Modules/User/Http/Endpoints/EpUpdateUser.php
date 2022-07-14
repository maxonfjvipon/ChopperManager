<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\RqUpdateUser;

/**
 * Update user endpoint.
 */
final class EpUpdateUser extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqUpdateUser $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn () => User::find($request->user)->update($request->userProps()),
                new TkRedirectToRoute('users.index')
            )
        );
    }
}
