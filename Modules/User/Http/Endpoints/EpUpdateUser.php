<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\RqUpdateUser;
use Symfony\Component\HttpFoundation\Response;

/**
 * Update user endpoint.
 */
final class EpUpdateUser extends Controller
{
    /**
     * @param RqUpdateUser $request
     * @param User $user
     * @return Responsable|Response
     */
    public function __invoke(RqUpdateUser $request, User $user): Responsable|Response
    {
        return (new TkWithCallback(
            function () use ($request, $user) {
                $user->update($request->userProps());
                $user->updateAvailablePropsFromRequest($request);
            },
            new TkRedirectToRoute('users.index')
        ))->act($request);
    }
}
