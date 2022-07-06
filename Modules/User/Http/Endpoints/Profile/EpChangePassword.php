<?php

namespace Modules\User\Http\Endpoints\Profile;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectBack;
use App\Takes\TkRedirectWith;
use App\Takes\TkTernary;
use App\Takes\TkWithCallback;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\RqChangePassword;

/**
 * Change password endpoint.
 */
final class EpChangePassword extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqChangePassword $request
     * @throws Exception
     */
    public function __construct(RqChangePassword $request)
    {
        parent::__construct(
            new TkTernary(
                $request->has('password') && !!$request->password,
                new TkWithCallback(
                    fn() => Auth::user()->update(['password' => Hash::make($request->password)]),
                    new TkRedirectWith(
                        "success",
                        __('flash.users.password_changed'),
                        $back = new TkRedirectBack()
                    )
                ),
                $back
            )
        );
    }
}
