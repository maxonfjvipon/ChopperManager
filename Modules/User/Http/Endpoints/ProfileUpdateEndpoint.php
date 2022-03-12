<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectedBack;
use App\Takes\TkRedirectedWith;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\UpdateProfileRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Profile update endpoint.
 */
final class ProfileUpdateEndpoint extends Controller
{
    /**
     * @param UpdateProfileRequest $request
     * @return Responsable|Response
     */
    public function __invoke(UpdateProfileRequest $request): Responsable|Response
    {
        return (new TkWithCallback(
            fn () => Auth::user()->update($request->validated()),
            new TkRedirectedWith(
                'success',
                __('flash.users.updated'),
                new TkRedirectedBack()
            )
        ))->act($request);
    }
}
