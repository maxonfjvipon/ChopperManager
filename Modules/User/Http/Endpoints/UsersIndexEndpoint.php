<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Support\ArrForFiltering;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\User\Entities\User;
use Modules\User\Entities\Business;
use Modules\User\Support\UsersToShow;
use Symfony\Component\HttpFoundation\Response;

final class UsersIndexEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkAuthorized(
            'user_access',
            new TkInertia(
                'User::Index',
                new UsersToShow()
            )
        ))->act();
    }
}
