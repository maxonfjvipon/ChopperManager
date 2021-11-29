<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Modules\User\Http\Requests\UserUpdatable;
use Modules\User\Services\UsersServicesInterface;

class UsersController extends Controller
{
    protected UsersServicesInterface $service;

    public function __construct(UsersServicesInterface $service)
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
        return $this->service->__index();
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
        return $this->service->__edit($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdatable $request
     * @param int $user_id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UserUpdatable $request, int $user_id): RedirectResponse
    {
        $this->authorize('user_edit');
        return $this->service->__update($request, $user_id);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('user_delete');
        return Redirect::back();
    }
}
