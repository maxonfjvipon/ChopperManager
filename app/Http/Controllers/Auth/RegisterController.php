<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Models\Users\Area;
use App\Models\Users\Business;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'inn' => $validated['inn'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'fio' => $validated['fio'],
            'city_id' => $validated['city_id'],
            'business_id' => $validated['business_id'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        return Redirect::route('verification.notice');
    }
}
