<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\Components\Actions\AcChassis;

/**
 * Chassis endpoint.
 */
final class EpChassis extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia('Components::Chassis', new AcChassis())
        );
    }
}
