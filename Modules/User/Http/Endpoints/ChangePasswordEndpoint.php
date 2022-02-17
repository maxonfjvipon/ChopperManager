<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectedBack;
use App\Takes\TkRedirectedWith;
use App\Takes\TkTernary;
use App\Takes\TkWithCallback;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\UserPasswordUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Change password endpoint.
 */
final class ChangePasswordEndpoint extends Controller
{
    /**
     * @param UserPasswordUpdateRequest $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(UserPasswordUpdateRequest $request): Responsable|Response
    {
        return TkTernary::new(
            $request->has('password') && $request->password != null,
            TkWithCallback::new(
                fn() => Auth::user()->update(['password' => Hash::make($request->password)]),
                TkRedirectedWith::new(
                    'success',
                    __('flash.users.password_changed'),
                    TkRedirectedBack::new()
                )
            ),
            TkRedirectedBack::new()
        )->act($request);
    }
}
