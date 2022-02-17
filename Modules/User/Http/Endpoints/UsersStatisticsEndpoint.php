<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Illuminate\Contracts\Support\Responsable;
use Modules\User\Support\UsersForStatistics;
use Symfony\Component\HttpFoundation\Response;

final class UsersStatisticsEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'user_access',
            TkInertia::new(
                'User::Statistics',
                UsersForStatistics::new()
            )
        )->act();
    }
}
