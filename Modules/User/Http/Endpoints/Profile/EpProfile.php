<?php

namespace Modules\User\Http\Endpoints\Profile;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkFromCallback;
use App\Takes\TkInertia;
use Modules\User\Actions\AcUserProfile;

/**
 * Profile endpoint.
 */
final class EpProfile extends TakeEndpoint
{
    public function __construct()
    {
        parent::__construct(
            new TkFromCallback(
                fn() => new TkInertia("User::Profile", new AcUserProfile())
            )
        );
    }
}
