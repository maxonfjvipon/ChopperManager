<?php

namespace App\Http\Controllers\Auth;

use App\Actions\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\CountryResource;
use App\Models\Users\Country;
use App\Models\Users\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    /**
     * Show register form
     *
     * @return Response
     */
    public function showRegisterForm(): Response
    {
        return Inertia::render('Auth/Register', [
            'businesses' => Business::all(),
            'countries' => Country::all()->map(function ($country) {
                return new CountryResource($country);
            })
        ]);
    }

    /**
     * Register user
     *
     * @param RegisterRequest $request
     * @param RegisterUserAction $action
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request, RegisterUserAction $action): RedirectResponse
    {
        $action->execute($request);
        return Redirect::route('verification.notice');
    }
}
