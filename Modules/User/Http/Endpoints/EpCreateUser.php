<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\User\Actions\AcCreateOrEditUser;

/**
 * Create user endpoint
 */
final class EpCreateUser extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                "User::Create",
                new AcCreateOrEditUser()
            )
        );
    }
}
