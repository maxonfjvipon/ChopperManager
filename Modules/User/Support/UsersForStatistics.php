<?php

namespace Modules\User\Support;

use App\Support\ArrForFiltering;
use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Numerable\ArraySum;
use Modules\Project\Entities\Project;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Entities\User;

/**
 * Users to show on users statistics page.
 */
final class UsersForStatistics implements Arrayable
{
    /**
     * {@inheritDoc}
     */
    public function asArray(): array
    {
        $rates = new StickyRates(new RealRates());
        $users = User::with(['country' => function ($query) {
            $query->select('id', 'name');
        }, 'business', 'projects' => function ($query) {
            $query->select('id', 'user_id');
        }, 'projects.selections' => function ($query) {
            $query->select('id', 'project_id', 'pump_id', 'pumps_count');
        }, 'projects.selections.pump' => function ($query) {
            $query->select('id', 'pumpable_type');
        }, 'projects.selections.pump.price_list', 'projects.selections.pump.price_list.currency',
        ])
            ->withCount('projects')
            ->get(['id', 'organization_name', 'business_id',
                'country_id', 'city', 'first_name', 'middle_name', 'last_name', 'last_login_at',
            ]);

        return (new ArrMerged(
            new ArrObject(
                'users',
                new ArrMapped(
                    $users->all(),
                    function (User $user) use ($rates) {
                        $projectsPrice = (new ArraySum(
                            new ArrMapped(
                                [...$user->projects],
                                fn (Project $project) => (new ArraySum(
                                    new ArrMapped(
                                        [...$project->selections],
                                        fn (Selection $selection) => $selection->totalRetailPrice($rates)
                                    )
                                ))->asNumber()
                            )
                        ))->asNumber();
                        $avgProjectsPrice = 0 !== $user->projects_count
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
                            'avg_projects_price' => round($avgProjectsPrice, 2),
                        ];
                    }
                )
            ),
            new ArrObject(
                'filter_data',
                new ArrForFiltering(
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
        ))->asArray();
    }
}
