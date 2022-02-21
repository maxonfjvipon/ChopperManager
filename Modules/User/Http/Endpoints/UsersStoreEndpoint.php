<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\CreateUserRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Users store endpoint.
 */
final class UsersStoreEndpoint extends Controller
{
    /**
     * @param CreateUserRequest $request
     * @return Responsable|Response
     */
    public function __invoke(CreateUserRequest $request): Responsable|Response
    {
        return TkAuthorized::new(
            'user_create',
            TkWithCallback::new(
                function () use ($request) {
                    $user = User::create($request->userProps());
                    $user->updateAvailablePropsFromRequest($request);
                    if ($request->email_verified) {
                        $user->markEmailAsVerified();
                    }
                    $user->assignRole('Client');
                },
                TkRedirectedRoute::new('users.index')
            )
        )->act($request);
    }
}
