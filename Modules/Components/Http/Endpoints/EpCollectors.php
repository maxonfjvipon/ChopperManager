<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\Components\Actions\AcCollectors;

/**
 * Collectors endpoint.
 */
final class EpCollectors extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia('Components::Collectors', new AcCollectors())
        );
    }
}
