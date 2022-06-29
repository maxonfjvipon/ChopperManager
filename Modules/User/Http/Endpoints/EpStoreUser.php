<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\User\Entities\User;
use Modules\User\Entities\UserPumpSeries;
use Modules\User\Http\Requests\RqStoreUser;

/**
 * Users store endpoint.
 */
final class EpStoreUser extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqStoreUser $request
     */
    public function __construct(RqStoreUser $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn() => UserPumpSeries::updateSeriesForUser(
                    $request->available_series_ids,
                    User::create($request->userProps())
                ),
                new TkRedirectToRoute('users.index')
            )
        );
    }
}
