<?php


namespace Modules\User\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\UserUpdatable;
use Modules\User\Transformers\UserResource;

class UsersServices implements UsersServicesInterface
{

    public function indexPath(): string
    {
        return 'User::Index';
    }

    public function showPath(): string
    {
    }

    public function editPath(): string
    {
        return 'User::Edit';
    }

    public function createPath(): string
    {
    }

    /**
     * @return Response
     */
    public function __index(): Response
    {
        return Inertia::render($this->indexPath(), [
            'users' => User::all(),
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function __edit(int $id): Response
    {
        return Inertia::render($this->editPath(), [
            'user' => new UserResource(User::find($id))
        ]);
    }

    /**
     * @param UserUpdatable $request
     * @param int $user_id
     * @return RedirectResponse
     */
    public function __update(UserUpdatable $request, int $user_id): RedirectResponse
    {
        User::find($user_id)->update($request->validated());
        return Redirect::route('users.index');
    }
}
