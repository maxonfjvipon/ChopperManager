<?php

namespace Modules\User\Http\Endpoints\Profile;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectBack;
use App\Takes\TkRedirectWith;
use App\Takes\TkTernary;
use App\Takes\TkWithCallback;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\RqChangePassword;
use Symfony\Component\HttpFoundation\Response;
use function __;

/**
 * Change password endpoint.
 */
final class EpChangePassword extends Controller
{
    /**
     * @param RqChangePassword $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(RqChangePassword $request): Responsable|Response
    {
        return (new TkTernary(
            $request->has('password') && $request->password != null,
            new TkWithCallback(
                fn() => Auth::user()->update(['password' => Hash::make($request->password)]),
                new TkRedirectWith(
                    'success',
                    __('flash.users.password_changed'),
                    new TkRedirectBack()
                )
            ),
            new TkRedirectBack()
        ))->act($request);
    }
}
