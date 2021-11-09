<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Auth\Actions\RegisterUserAction;
use Modules\Auth\Traits\CanRegisterUser;
use Modules\Core\Support\Executable;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Transformers\CountryResource;

class RegisterController extends Controller
{
    /**
     * Show register form
     *
     * @return Response
     */
    public function showRegisterForm(): Response
    {
        return Inertia::render('Auth::Register', [
            'businesses' => Business::all(),
            'countries' => Country::all()->map(function ($country) {
                return new CountryResource($country);
            })
        ]);
    }

    protected function __register($request, RegisterUserAction $action): RedirectResponse
    {
        $action->execute($request);
        return Redirect::route('verification.notice');
    }
}
