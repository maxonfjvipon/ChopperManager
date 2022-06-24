<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\Components\Actions\AcControlSystems;

/**
 * Control systems endpoint.
 */
final class EpControlSystems extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia('Components::ControlSystems', new AcControlSystems())
        );
    }
}
