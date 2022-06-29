<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Http\Request;
use Modules\User\Actions\AcCreateOrEditUser;
use Modules\User\Entities\User;

/**
 * Users edit endpoint
 */
final class EpEditUser extends TakeEndpoint
{
    /**
     * Ctor.
     * @param Request $request
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                "User::Edit",
                new AcCreateOrEditUser(
                    User::find($request->user)
                )
            )
        );
    }
}
