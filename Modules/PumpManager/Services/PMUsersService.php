<?php


namespace Modules\PumpManager\Services;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\Pump\Entities\PumpSeries;
use Modules\PumpManager\Entities\PMUser;
use Modules\PumpManager\Transformers\PMUserResource;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Services\UsersServices;
use Modules\User\Transformers\CountryResource;
use PHPUnit\Framework\Constraint\Count;

class PMUsersService extends UsersServices
{
    /**
     * @return array
     * @throws Exception
     */
    private function filterData(): array
    {
        return [
            'selection_types' => SelectionType::all(['id', 'name']),
            'series' => ArrMapped::new(
                [...PumpSeries::with('brand')->get()],
                fn(PumpSeries $series) => [
                    'id' => $series->id,
                    'name' => $series->brand->name . " " . $series->name
                ]
            )->asArray(),
            'businesses' => Business::allOrCached(),
            'countries' => ArrMapped::new(
                [...Country::allOrCached()],
                fn(Country $country) => [
                    'id' => $country->id,
                    'name' => $country->country_code
                ]
            )->asArray()
        ];
    }

    /**
     * @param int $user_id
     * @return Response
     */
    public function edit(int $user_id): Response
    {
        return Inertia::render($this->editPath(), [
            'user' => new PMUserResource(PMUser::find($user_id)),
            'filter_data' => $this->filterData()
        ]);
    }

    /**
     * @param UpdateUserRequest $request
     * @param int $user_id
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, int $user_id): RedirectResponse
    {
        $user = PMUser::find($user_id);
        $user->update($request->userProps());
        $user->updateAvailablePropsFromRequest($request);
        return Redirect::route('users.index');
    }

    /**
     * @param CreateUserRequest $request
     * @return RedirectResponse
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        $user = PMUser::create($request->userProps());
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
    public function index(): Response
    {
        return parent::__index(PMUser::with(['country' => function ($query) {
            $query->select('id', 'name');
        }])->get(['id', 'created_at', 'email', 'organization_name',
            'city', 'country_id', 'first_name', 'middle_name', 'last_name'])->all());
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render($this->createPath(), $this->filterData());
    }
}
