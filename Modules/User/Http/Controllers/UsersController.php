<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\User\Entities\User;
use Modules\User\Transformers\UserResource;

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
            'users' => User::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id): Response
    {
        return Inertia::render('User::Edit', [
            'user' => new UserResource(User::find($id))
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param User $user
     * @return Renderable
     */
    public function update(Request $request, $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
