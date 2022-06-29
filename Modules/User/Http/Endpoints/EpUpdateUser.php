<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\User\Entities\User;
use Modules\User\Entities\UserPumpSeries;
use Modules\User\Http\Requests\RqUpdateUser;

/**
 * Update user endpoint.
 */
final class EpUpdateUser extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqUpdateUser $request
     */
    public function __construct(RqUpdateUser $request)
    {
        parent::__construct(
            new TkWithCallback(
                function() use ($request) {
                    ($user = User::find($request->user))->update($request->userProps());
                    UserPumpSeries::updateSeriesForUser($request->available_series_ids, $user);
                },
                new TkRedirectToRoute('users.index')
            )
        );
    }
}
