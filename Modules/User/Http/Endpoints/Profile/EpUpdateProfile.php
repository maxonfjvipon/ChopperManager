<?php

namespace Modules\User\Http\Endpoints\Profile;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectBack;
use App\Takes\TkRedirectWith;
use App\Takes\TkWithCallback;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\RqUpdateProfile;

/**
 * Update profile endpoint.
 */
final class EpUpdateProfile extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqUpdateProfile $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn () => Auth::user()->update($request->validated()),
                new TkRedirectWith(
                    'success',
                    __('flash.users.updated'),
                    new TkRedirectBack()
                )
            )
        );
    }
}
