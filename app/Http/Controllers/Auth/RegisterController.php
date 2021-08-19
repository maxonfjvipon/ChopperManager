<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\users\User;
use App\Models\users\Area;
use App\Models\users\Business;
use App\Models\users\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
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
            'roles' => Role::where('id', '!=', 1)->get(),
            'businesses' => Business::all(),
            'areasWithCities' => Area::with('cities')->get(),
        ]);
    }

    /**
     * Register user
     *
     * @param RegisterRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
