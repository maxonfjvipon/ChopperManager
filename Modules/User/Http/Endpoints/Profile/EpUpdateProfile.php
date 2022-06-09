<?php

namespace Modules\User\Http\Endpoints\Profile;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectBack;
use App\Takes\TkRedirectWith;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\RqUpdateProfile;
use Symfony\Component\HttpFoundation\Response;

/**
 * Update profile endpoint.
 */
final class EpUpdateProfile extends Controller
{
    /**
     * @param RqUpdateProfile $request
     * @return Responsable|Response
     */
    public function __invoke(RqUpdateProfile $request): Responsable|Response
    {
        return (new TkWithCallback(
            fn () => Auth::user()->update($request->validated()),
            new TkRedirectWith(
                'success',
                __('flash.users.updated'),
                new TkRedirectBack()
            )
        ))->act($request);
    }
}
