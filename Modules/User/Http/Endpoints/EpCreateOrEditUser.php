<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Http\Request;
use Modules\User\Actions\AcCreateOrEditUser;
use Modules\User\Entities\User;

/**
 * Create user endpoint.
 */
final class EpCreateOrEditUser extends TakeEndpoint
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                'User::CreateOrEdit',
                new AcCreateOrEditUser(User::find($request->user))
            )
        );
    }
}
