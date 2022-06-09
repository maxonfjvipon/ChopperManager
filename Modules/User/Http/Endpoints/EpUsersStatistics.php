<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\User\Support\UsersForStatistics;
use Symfony\Component\HttpFoundation\Response;

/**
 * Users statistics endpoint.
 */
final class EpUsersStatistics extends Controller
{
    /**
     *
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkInertia(
            'User::Statistics',
            new UsersForStatistics()
        ))->act();
    }
}
