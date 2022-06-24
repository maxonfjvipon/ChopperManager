<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Http\Endpoints\TakeEndpoint;
use App\Interfaces\Take;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\User\Entities\Area;
use Symfony\Component\HttpFoundation\Response;

/**
 * Register endpoint.
 */
final class EpRegister extends TakeEndpoint
{
    /**
     * Ctor.
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
