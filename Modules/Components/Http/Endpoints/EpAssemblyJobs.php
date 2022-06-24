<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\Components\Actions\AcAssemblyJobs;

/**
 * Assembly jobs endpoint.
 */
final class EpAssemblyJobs extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                'Components::AssemblyJobs',
                new AcAssemblyJobs()
            )
        );
    }
}
