<?php

namespace Modules\Project\Support;

use App\Support\ArrForFiltering;
use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use Illuminate\Support\Facades\DB;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Numerable\ArraySum;
use Maxonfjvipon\Elegant_Elephant\Numerable\Rounded;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectDeliveryStatus;
use Modules\Project\Entities\ProjectStatus;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;

/**
 * Projects for statistics.
 */
final class ProjectsForStatistics implements Arrayable
{
    /**
     * @return ProjectsForStatistics
     */
    public static function new(): ProjectsForStatistics
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $rates = StickyRates::new(RealRates::new());
        $statuses = ProjectStatus::allOrCached()->all();
        $deliveryStatuses = ProjectDeliveryStatus::allOrCached()->all();
        $usersData = DB::table('users')
            ->select('city', 'organization_name', 'country_id', 'business_id')->get();
        return ArrMerged::new(
            [
                'project_statuses' => $statuses,
                'delivery_statuses' => $deliveryStatuses
            ],
            ArrObject::new(
                "projects",
                ArrMapped::new(
                    Project::withTrashed()
                        ->withCount('all_selections')
                        ->with(['user' => function ($query) {
                                $query->select('id', 'first_name', 'middle_name',
                                    'last_name', 'organization_name', 'city', 'country_id', 'business_id');
                            }, 'user.country', 'user.business', 'all_selections' => function ($query) {
                                $query->select('id', 'pumps_count', 'pump_id', 'project_id');
                            }, 'all_selections.pump' => function ($query) {
                                $query->select('id', 'pumpable_type');
                            }, 'all_selections.pump.price_list', 'all_selections.pump.price_list.currency']
                        )->get()->all(),
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
                            'price' => Rounded::new(
                                ArraySum::new(
                                    ArrMapped::new(
                                        [...$project->all_selections],
                                        fn(Selection $selection) => $selection->totalRetailPrice($rates)
                                    )
                                ),
                                2
                            )->asNumber(),
                            'status_id' => $project->status_id,
                            'delivery_status_id' => $project->delivery_status_id,
                            'comment' => $project->comment
                        ];
                    }
                )
            ),
            ArrObject::new(
                "filter_data",
                ArrMerged::new(
                    ArrMapped::new([
                        'project_statuses' => $statuses,
                        'delivery_statuses' => $deliveryStatuses,
                    ], fn(array $arr) => ArrMapped::new(
                        $arr,
                        fn($item) => [
                            'text' => $item->name,
                            'value' => $item->id
                        ])->asArray()
                    ),
                    ArrForFiltering::new([
                        'countries' => Country::allOrCached()->whereIn('id', $usersData->pluck('country_id')->all())
                            ->pluck('name')->all(),
                        'businesses' => Business::allOrCached()->whereIn('id', $usersData->pluck('business_id')->all())
                            ->pluck('name')->all(),
                        'cities' => $usersData->unique('city')->sortBy('city')->pluck('city')->all(),
                        'organizations' => $usersData->unique('organization_name')->sortBy('organization_name')
                            ->pluck('organization_name')->all()
                    ])
                )
            )
        )->asArray();
    }
}
