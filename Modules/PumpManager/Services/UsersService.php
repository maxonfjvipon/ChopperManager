<?php


namespace Modules\PumpManager\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\Pump\Entities\PumpSeries;
use Modules\PumpManager\Entities\User;
use Modules\PumpManager\Transformers\UserResource;
use Modules\User\Http\Requests\UserUpdatable;
use Modules\User\Services\UsersServices;

class UsersService extends UsersServices
{
    /**
     * @return Response
     */
    public function __index(): Response
    {
        return Inertia::render($this->indexPath(), [
            'users' => User::with(['country' => function ($query) {
                $query->select('id', 'name');
            }])->get(['id', 'created_at', 'email', 'organization_name',
                'city', 'country_id', 'first_name', 'middle_name', 'last_name'])->all()
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function __edit(int $id): Response
    {
        return Inertia::render($this->editPath(), [
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
     * @param UserUpdatable $request
     * @param int $user_id
     * @return RedirectResponse
     */
    public function __update(UserUpdatable $request, int $user_id): RedirectResponse
    {
        User::find($user_id)->updateAvailablePropsFromRequest($request);
        return Redirect::route('users.index');
    }

}
