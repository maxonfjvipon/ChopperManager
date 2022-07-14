<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Http\Request;
use Modules\User\Actions\AcShowUser;
use Modules\User\Entities\User;

/**
 * Users edit endpoint.
 */
final class EpShowUser extends TakeEndpoint
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
                'User::Show',
                new AcShowUser(User::find($request->user))
            )
        );
    }
}
