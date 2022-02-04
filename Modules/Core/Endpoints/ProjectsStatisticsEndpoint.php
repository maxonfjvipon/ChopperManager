<?php

namespace Modules\Core\Endpoints;

use App\Http\Controllers\Controller;
use App\Support\ArrForFiltering;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\ProjectDeliveryStatus;
use Modules\Core\Entities\ProjectStatus;
use Modules\Core\Support\Rates;
use Modules\Selection\Entities\Selection;
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
                    return [
                        'projects' => ArrMapped::new(
                            [...Project::withCount('selections')
                                ->with(['user' => function ($query) use ($rates) {
                                    $query->select('id', 'first_name', 'middle_name');
                                }, 'selections', 'selections.pump', 'selections.pump.price_list',
                                    'selections.pump.series.discount', 'selections.pump.price_list.currency'
                                ])
                                ->get()],
                            fn(Project $project) => [
                                'key' => $project->id,
                                'id' => $project->id,
                                'created_at' => date_format($project->created_at, "d.m.Y"),
                                'user' => TxtImploded::new(
                                    " ",
                                    $project->user->first_name,
                                    $project->user->middle_name
                                )->asString(),
                                'name' => $project->name,
                                'selections_count' => $project->selections_count,
                                'price' => array_sum(
                                    ArrMapped::new(
                                        [...$project->selections],
                                        fn(Selection $selection) => $selection->withPrices($rates)->retail_price
                                    )->asArray()
                                ),
                                'status_id' => $project->status_id,
                                'delivery_status_id' => $project->delivery_status_id,
                                'comment' => $project->comment
                            ]
                        )->asArray(),
                        'project_statuses' => $statuses,
                        'delivery_statuses' => $deliveryStatuses,
                        'filter_data' => ArrMapped::new([
                            'project_statuses' => [...$statuses],
                            'delivery_statuses' => [...$deliveryStatuses],
                        ], fn(array $arr) => ArrMapped::new(
                            $arr,
                            fn($item) => [
                                'text' => $item->name,
                                'value' => $item->id
                            ])->asArray()
                        )->asArray(),
                    ];
                })
        )->act();
    }
}
