<?php

namespace Modules\User\Endpoints;

use App\Http\Controllers\Controller;
use App\Support\ArrForFiltering;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Numerable\ArraySum;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;
use Modules\Core\Entities\Project;
use Modules\Core\Support\Rates;
use Modules\PumpManager\Entities\PMUser;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Business;
use Symfony\Component\HttpFoundation\Response;

class UsersIndexEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     * @throws \Exception
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'user_access',
            TkInertia::new('User::Index', function () {
                return [
                    'filter_data' => ArrForFiltering::new(
                        ['businesses' => Business::allOrCached()->pluck('name')->all()]
                    )->asArray(),
                    'users' => ArrMapped::new(
                        [...PMUser::with(['country' => function ($query) {
                            $query->select('id', 'name');
                        }, 'business'])
                            ->withCount('projects')
                            ->get(['id', 'organization_name', 'business_id', 'created_at',
                                'country_id', 'city', 'first_name', 'middle_name', 'last_name', 'is_active', 'phone', 'email'
                            ])],
                        fn(PMUser $user) => [
                            'id' => $user->id,
                            'key' => $user->id,
                            'created_at' => date_format($user->created_at, 'd.m.Y'),
                            'organization_name' => $user->organization_name,
                            'full_name' => $user->full_name,
                            'phone' => $user->phone,
                            'email' => $user->email,
                            'business' => $user->business->name,
                            'country' => $user->country->name,
                            'city' => $user->city,
                            'is_active' => $user->is_active
                        ]
                    )->asArray()
                ];
            })
        )->act();
    }
}
