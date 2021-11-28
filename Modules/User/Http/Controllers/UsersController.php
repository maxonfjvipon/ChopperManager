<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ModuleResourceController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\PumpManager\Http\Requests\UpdateUserRequest;
use Modules\User\Entities\User;
use Modules\User\Transformers\UserResource;

class UsersController extends ModuleResourceController
{
    public function __construct()
    {
        parent::__construct(
            'User::Index',
            null,
            null,
            'User::Edit'
        );
    }

    /**
     * Display a listing of the resource.
     * @return Response
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize('user_access');
        return Inertia::render($this->indexPath, [
            'users' => User::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        return Inertia::render($this->editPath, [
            'user' => new UserResource(User::find($id))
        ]);
    }

    // FIXME

    /**
     * Update the specified resource in storage.
     * @param UpdateUserRequest $request
     * @param $user_id
     * @return Renderable
     */
    public function update(UpdateUserRequest $request, $user_id)
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
