<?php

namespace Modules\User\Support;

use App\Support\ArrForFiltering;
use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\Elegant_Elephant\Numerable\ArraySum;
use Modules\Core\Entities\Project;
use Modules\PumpManager\Entities\PMUser;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;

/**
 * Users to show on users statistics page.
 */
final class UsersForStatistics implements Arrayable
{
    /**
     * Ctor wrap.
     * @return UsersForStatistics
     */
    public static function new(): UsersForStatistics
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $rates = StickyRates::new(RealRates::new());
        $users = PMUser::with(['country' => function ($query) {
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
            ]);
        return ArrMerged::new(
            ArrObject::new(
                "users",
                ArrMapped::new(
                    $users->all(),
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
                        $avgProjectsPrice = $user->projects_count !== 0
                            ? $projectsPrice / $user->projects_count
                            : 0;
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
                            'total_projects_price' => round($projectsPrice, 2),
                            'avg_projects_price' => round($avgProjectsPrice, 2)
                        ];
                    }
                )
            ),
            ArrObject::new(
                "filter_data",
                ArrForFiltering::new(
                    [
                        'businesses' => Business::allOrCached()->whereIn('id', $users->pluck('business_id')->all())
                            ->pluck('name')->all(),
                        'countries' => Country::allOrCached()->whereIn('id', $users->pluck('country_id')->all())
                            ->pluck('name')->all(),
                        'cities' => $users->unique('city')->sortBy('city')->pluck('city')->all(),
                        'organizations' => $users->unique('organization_name')->sortBy('organization_name')
                            ->pluck('organization_name')->all(),
                    ]
                )
            ),
        )->asArray();
    }
}
