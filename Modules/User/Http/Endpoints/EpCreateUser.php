<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\User\Support\UsersFilterData;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create user endpoint
 */
final class EpCreateUser extends Controller
{
    /**
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkInertia(
            'User::Create',
            new UsersFilterData()
        ))->act();
    }
}
