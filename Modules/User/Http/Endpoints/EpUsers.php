<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\User\Actions\AcUsers;

/**
 * Users endpoint.
 */
final class EpUsers extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                'User::Index',
                new AcUsers()
            )
        );
    }
}
