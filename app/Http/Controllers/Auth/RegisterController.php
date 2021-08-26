<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Pumps\PumpProducer;
use App\Models\Pumps\PumpSeries;
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

//        $user->discounts()->insert(array_map(function ($series) use ($user) {
//            return ['user_id' => $user->id, 'series_id' => $series['id']];
//        }, PumpSeries::all()->toArray()));

        $user->discounts()->insert(array_map(function ($series) use ($user) {
            return ['user_id' => $user->id, 'discountable_id' => $series['id'], 'discountable_type' => 'pump_series'];
        }, PumpSeries::all()->toArray()));

        $user->discounts()->insert(array_map(function ($producer) use ($user) {
            return ['user_id' => $user->id, 'discountable_id' => $producer['id'], 'discountable_type' => 'pump_producer'];
        }, PumpProducer::all()->toArray()));

        event(new Registered($user));

        Auth::login($user);

        return Redirect::route('verification.notice');
    }
}
