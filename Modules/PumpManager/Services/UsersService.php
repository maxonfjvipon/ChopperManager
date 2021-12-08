<?php


namespace Modules\PumpManager\Services;

use App\Traits\HasFilterData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\Pump\Entities\PumpSeries;
use Modules\PumpManager\Entities\User;
use Modules\PumpManager\Transformers\UserResource;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Http\Requests\Interfaces\UserCreatable;
use Modules\User\Http\Requests\Interfaces\UserUpdatable;
use Modules\User\Services\UsersServices;
use Modules\User\Transformers\CountryResource;

class UsersService extends UsersServices
{
    /**
     * @return array
     */
    private function filterData(): array
    {
        return [
            'selection_types' => SelectionType::all(['id', 'name']),
            'series' => PumpSeries::with('brand')->get()->map(fn($series) => [
                'id' => $series->id,
                'name' => $series->brand->name . " " . $series->name,
            ])->all(),
            'businesses' => Business::all(),
            'countries' => Country::all()->map(function ($country) {
                return new CountryResource($country);
            })
        ];
    }

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
            'filter_data' => $this->filterData()
        ]);
    }

    /**
     * @param UserUpdatable $request
     * @param int $user_id
     * @return RedirectResponse
     */
    public function __update(UserUpdatable $request, int $user_id): RedirectResponse
    {
        $user = User::find($user_id);
        $user->update($request->userProps());
        $user->updateAvailablePropsFromRequest($request);
        return Redirect::route('users.index');
    }

    /**
     * @param UserCreatable $request
     * @return RedirectResponse
     */
    public function __store(UserCreatable $request): RedirectResponse
    {
        $user = User::create($request->userProps());
        $user->updateAvailablePropsFromRequest($request);
        if ($request->email_verified) {
            $user->markEmailAsVerified();
        }
        $user->assignRole('Client');
        return Redirect::route('users.index');
    }

    /**
     * @return Response
     */
    public function __create(): Response
    {
        return Inertia::render($this->createPath(), $this->filterData());
    }
}
