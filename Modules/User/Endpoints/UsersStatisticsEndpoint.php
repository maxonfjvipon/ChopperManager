<?php

namespace Modules\User\Endpoints;

use App\Http\Controllers\Controller;
use App\Support\ArrForFiltering;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Numerable\ArraySum;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Core\Entities\Project;
use Modules\Core\Support\Rates;
use Modules\PumpManager\Entities\PMUser;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Business;
use Symfony\Component\HttpFoundation\Response;

class UsersStatisticsEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'user_access',
            TkInertia::new('User::Statistics', function () {
                $rates = Rates::new();
                return [
                    'filter_data' => ArrForFiltering::new(
                        ['businesses' => Business::allOrCached()->pluck('name')->all()]
                    )->asArray(),
                    'users' => ArrMapped::new(
                        [...PMUser::with(['country' => function ($query) {
                            $query->select('id', 'name');
                        }, 'business', 'projects' => function ($query) {
                            $query->select('id', 'user_id');
                        }, 'projects.selections' => function ($query) {
                            $query->select('id', 'project_id', 'pump_id', 'pumps_count');
                        }, 'projects.selections.pump' => function ($query) {
                            $query->select('id', 'pumpable_type');
                        }, 'projects.selections.pump.price_list', 'projects.selections.pump.price_list.currency'
                        ])
                            ->withCount('projects')
                            ->get(['id', 'organization_name', 'business_id',
                                'country_id', 'city', 'first_name', 'middle_name', 'last_name', 'last_login_at'
                            ])],
                        function (PMUser $user) use ($rates) {
                            $projectsPrice = ArraySum::new(
                                ArrMapped::new(
                                    [...$user->projects],
                                    fn(Project $project) => ArraySum::new(
                                        ArrMapped::new(
                                            [...$project->selections],
                                            fn(Selection $selection) => $selection->totalRetailPrice($rates)
                                        )
                                    )->asNumber()
                                )
                            )->asNumber();
                            return [
                                'id' => $user->id,
                                'key' => $user->id,
                                'last_login_at' => date_format($user->last_login_at, 'd.m.Y'),
                                'organization_name' => $user->organization_name,
                                'full_name' => $user->full_name,
                                'business' => $user->business->name,
                                'country' => $user->country->name,
                                'city' => $user->city,
                                'projects_count' => $user->projects_count,
                                'total_projects_price' => number_format($projectsPrice, 1),
                                'avg_projects_price' => $user->projects_count !== 0
                                    ? number_format($projectsPrice / $user->projects_count, 1)
                                    : 0
                            ];
                        }
                    )->asArray()
                ];
            })
        )->act();
    }
}
