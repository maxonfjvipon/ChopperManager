<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpManager\Entities\PMUser;
use Modules\User\Http\Requests\UpdateUserRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Users update endpoint.
 */
final class UsersUpdateEndpoint extends Controller
{
    /**
     * @param UpdateUserRequest $request
     * @param int $user_id
     * @return Responsable|Response
     */
    public function __invoke(UpdateUserRequest $request, int $user_id): Responsable|Response
    {
        return TkAuthorized::new(
            'user_edit',
            TkWithCallback::new(
                function () use ($request, $user_id) {
                    $user = PMUser::find($user_id);
                    $user->update($request->userProps());
                    $user->updateAvailablePropsFromRequest($request);
                },
                TkRedirectedRoute::new(
                    'users.index'
                )
            )
        )->act($request);
    }
}
