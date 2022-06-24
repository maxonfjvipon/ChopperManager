<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;

/**
 * Await endpoint.
 */
final class EpAwait extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(new TkInertia("Auth::Await"));
    }
}
