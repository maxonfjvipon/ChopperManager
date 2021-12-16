<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Traits\HasUserable;
use Modules\User\Transformers\CountryResource;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class RegisterController extends Controller
{
    use HasUserable, UsesTenantModel;

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

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = $this->createdUser($request->userProps());

        event(new Registered($user));
        Auth::guard($this->getTenantModel()::current()->guard)->login($user);

        return Redirect::route('verification.notice');
    }
}
