<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Exception;
use Modules\User\Entities\Area;

/**
 * Register endpoint.
 */
final class EpRegister extends TakeEndpoint
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                'Auth::Register',
                ['areas' => Area::allOrCached()]
            )
        );
    }
}
