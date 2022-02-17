<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Illuminate\Contracts\Support\Responsable;
use Modules\User\Support\UsersFilterData;
use Symfony\Component\HttpFoundation\Response;

/**
 * Users create endpoint
 */
final class UsersCreateEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'user_create',
            TkInertia::new(
                'User::Create',
                UsersFilterData::new()
            )
        )->act();
    }
}
