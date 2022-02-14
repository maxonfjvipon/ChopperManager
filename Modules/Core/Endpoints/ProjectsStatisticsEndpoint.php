<?php

namespace Modules\Core\Endpoints;

use App\Http\Controllers\Controller;
use App\Support\ArrForFiltering;
use App\Support\FormattedPrice;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Numerable\ArraySum;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\ProjectDeliveryStatus;
use Modules\Core\Entities\ProjectStatus;
use Modules\Core\Support\Rates;
use Modules\PumpManager\Entities\PMUser;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects statistics endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsStatisticsEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'project_statistics',
            TkInertia::new(
                "Core::Projects/Statistics",
                function () {
                    $rates = Rates::new();
                    $statuses = ProjectStatus::allOrCached();
                    $deliveryStatuses = ProjectDeliveryStatus::allOrCached();
                    $usersData = DB::table(Tenant::current()->database . '.users')
                        ->select('city', 'organization_name', 'country_id', 'business_id')->get();
                    $cities = [...$usersData->unique('city')->sortBy('city')->pluck('city')->all()];
                    $organizations = [...$usersData->unique('organization_name')->sortBy('organization_name')->pluck('organization_name')->all()];
                    return [
                        'projects' => ArrMapped::new(
                            [...Project::withTrashed()
                                ->withCount('all_selections')
                                ->with(['user' => function ($query) {
                                        $query->select('id', 'first_name', 'middle_name',
                                            'last_name', 'organization_name', 'city', 'country_id', 'business_id');
                                    }, 'user.country', 'user.business', 'all_selections' => function ($query) {
                                        $query->select('id', 'pumps_count', 'pump_id', 'project_id');
                                    }, 'all_selections.pump' => function ($query) {
                                        $query->select('id', 'pumpable_type');
                                    }, 'all_selections.pump.price_list', 'all_selections.pump.price_list.currency']
                                )->get()],
                            function (Project $project) use ($rates) {
                                return [
                                    'key' => $project->id,
                                    'id' => $project->id,
                                    'created_at' => date_format($project->created_at, "d.m.Y"),
                                    'name' => $project->name,
                                    'user_organization_name' => $project->user->organization_name,
                                    'user_full_name' => $project->user->full_name,
                                    'user_business' => $project->user->business->name,
                                    'country' => $project->user->country->name,
                                    'city' => $project->user->city,
                                    'selections_count' => $project->all_selections_count,
                                    'price' => round(
                                        ArraySum::new(
                                            ArrMapped::new(
                                                [...$project->all_selections],
                                                fn(Selection $selection) => $selection->totalRetailPrice($rates)
                                            )
                                        )->asNumber(),
                                        2
                                    ),
                                    'status_id' => $project->status_id,
                                    'delivery_status_id' => $project->delivery_status_id,
                                    'comment' => $project->comment
                                ];
                            }
                        )->asArray(),
                        'project_statuses' => $statuses,
                        'delivery_statuses' => $deliveryStatuses,
                        'filter_data' => ArrMerged::new(
                            ArrMapped::new([
                                'project_statuses' => [...$statuses],
                                'delivery_statuses' => [...$deliveryStatuses],
                            ], fn(array $arr) => ArrMapped::new(
                                $arr,
                                fn($item) => [
                                    'text' => $item->name,
                                    'value' => $item->id
                                ])->asArray(),
                            ),
                            ArrForFiltering::new([
                                'countries' => Country::allOrCached()->whereIn('id', $usersData->pluck('country_id')->all())
                                    ->pluck('name')->all(),
                                'businesses' => Business::allOrCached()->whereIn('id', $usersData->pluck('business_id')->all())
                                    ->pluck('name')->all(),
                                'cities' => $cities,
                                'organizations' => $organizations
                            ])
                        )->asArray()
                    ];
                })
        )->act();
    }
}
