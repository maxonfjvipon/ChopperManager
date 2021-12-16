<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Modules\User\Contracts\UsersServiceContract;
use Modules\User\Entities\Userable;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Traits\HasUserable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class UsersController extends Controller
{
    use UsesTenantModel, HasUserable;

    protected UsersServiceContract $service;

    public function __construct(UsersServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize('user_access');
        return $this->service->index();
    }

    /**
     * Show the form for creating the user
     *
     * @throws AuthorizationException
     */
    public function create(): Response
    {
        $this->authorize('user_create');
        return $this->service->create();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $user_id
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(int $user_id): Response
    {
        $this->authorize('user_edit');
        return $this->service->edit($user_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param int $user_id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateUserRequest $request, int $user_id): RedirectResponse
    {
        $this->authorize('user_edit');
        return $this->service->update($request, $user_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        $this->authorize('user_create');
        return $this->service->store($request);
    }

    /**
     * TODO: Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('user_delete');
        return Redirect::back()->with('warning', "Can't delete user. Try disable");
    }
}
