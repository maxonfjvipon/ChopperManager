<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\RqStoreUser;
use Symfony\Component\HttpFoundation\Response;

/**
 * Users store endpoint.
 */
final class EpStoreUser extends Controller
{
    /**
     * @param RqStoreUser $request
     * @return Responsable|Response
     */
    public function __invoke(RqStoreUser $request): Responsable|Response
    {
        return (new TkWithCallback(
            function () use ($request) {
                $user = User::create($request->userProps());
                $user->updateAvailablePropsFromRequest($request);
                if ($request->email_verified) {
                    $user->markEmailAsVerified();
                }
                $user->assignRole('Client');
            },
            new TkRedirectToRoute('users.index')
        ))->act($request);
    }
}
