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
use Inertia\Testing\Concerns\Has;
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
        return (new TkTernary(
            $request->has('password') && $request->password != null,
            new TkWithCallback(
                fn() => Auth::user()->update(['password' => Hash::make($request->password)]),
                new TkRedirectedWith(
                    'success',
                    __('flash.users.password_changed'),
                    new TkRedirectedBack()
                )
            ),
            new TkRedirectedBack()
        ))->act($request);
    }
}
