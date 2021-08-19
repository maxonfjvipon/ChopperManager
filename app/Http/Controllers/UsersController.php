<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\users\Area;
use App\Models\users\Business;
use App\Models\users\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    /**
     * Display a user profile.
     *
     * @return Response
     */
    public function profile(): Response
    {
        return Inertia::render('Profile', [
            'user' => new UserResource(auth()->user()),
            'roles' => Role::where('id', '!=', 1)->get(),
            'businesses' => Business::all(),
            'areasWithCities' => Area::with('cities')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(UserUpdateRequest $request): RedirectResponse
    {
        Auth::user()->update($request->validated());
        return Redirect::back()->with('success', 'Информация успешно обновлена');
    }
}
