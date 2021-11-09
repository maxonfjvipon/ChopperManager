<?php

namespace Modules\PumpManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\Pump\Entities\PumpSeries;
use Modules\PumpManager\Entities\User;
use Modules\PumpManager\Http\Requests\UpdateUserRequest;
use Modules\PumpManager\Transformers\UserResource;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize('user_access');
        return Inertia::render('User::Index', [
            'users' => User::with(['country' => function ($query) {
                $query->select('id', 'name');
            }])->get(['id', 'created_at', 'email', 'organization_name',
                'city', 'country_id', 'first_name', 'middle_name', 'last_name'])->all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(int $id): Response
    {
        $this->authorize('user_edit');
        return Inertia::render('User::Edit', [
            'user' => new UserResource(User::find($id)),
            'filter_data' => [
                'selection_types' => SelectionType::all(['id', 'name']),
                'series' => PumpSeries::with('brand')->get()->map(fn($series) => [
                    'id' => $series->id,
                    'name' => $series->brand->name . " " . $series->name,
                ])->all()
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateUserRequest $request
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('user_edit');
        $user->updateAvailablePropsFromRequest($request);
        return Redirect::route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy($id): RedirectResponse
    {
        $this->authorize('user_delete');
        return Redirect::back();
    }
}
