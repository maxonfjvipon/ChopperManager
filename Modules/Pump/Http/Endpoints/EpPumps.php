<?php

namespace Modules\Pump\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Http\Endpoints\TakeEndpoint;
use App\Interfaces\Take;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;
use Modules\Pump\Actions\AcPumps;
use Modules\Pump\Http\Requests\RqLoadPumps;

/**
 * Pumps endpoint.
 */
final class EpPumps extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia("Pump::Index", new AcPumps())
        );
    }
}
