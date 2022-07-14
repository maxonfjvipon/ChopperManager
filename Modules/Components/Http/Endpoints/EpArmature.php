<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\Components\Actions\AcArmature;

/**
 * Armature endpoint.
 */
final class EpArmature extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                'Components::Armature',
                new AcArmature()
            )
        );
    }
}
