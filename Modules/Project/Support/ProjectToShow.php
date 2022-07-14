<?php

namespace Modules\Project\Support;

use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Project\Entities\Project;
use Modules\Selection\Entities\Selection;

/**
 * Project to show.
 */
final class ProjectToShow implements Arrayable
{
    private Project $project;

    /**
     * Ctor.
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return array[]
     *
     * @throws Exception
     */
    public function asArray(): array
    {
        $rates = new StickyRates(new RealRates());

        return [
            'project' => [
                'data' => [
                    'id' => $this->project->id,
                    'name' => $this->project->name,
                    'selections' => (new ArrMapped(
                        Selection::withOrWithoutTrashed()
                            ->whereProjectId($this->project->id)
                            ->with([
                                'pump' => function ($query) {
                                    $query->select('id', 'name', 'rated_power', 'series_id', 'article_num_main', 'pumpable_type');
                                },
                                'pump.price_list',
                                'pump.price_list.currency',
                                'pump.series',
                                'pump.series.auth_discount',
                            ])
                            ->get(['pump_id', 'selected_pump_name', 'pumps_count', 'head', 'flow', 'id', 'created_at'])
                            ->all(),
                        function (Selection $selection) use ($rates) {
                            $selection = $selection->withPrices($rates);

                            return [
                                'id' => $selection->id,
                                'pump_id' => $selection->pump->id,
                                'article_num' => $selection->pump->article_num_main,
                                'created_at' => $selection->created_at->format('d.m.Y'),
                                'flow' => $selection->flow,
                                'head' => $selection->head,
                                'selected_pump_name' => $selection->selected_pump_name,
                                'discounted_price' => round($selection->discounted_price, 2),
                                'total_discounted_price' => round($selection->total_discounted_price, 2),
                                'rated_power' => $selection->pump_rated_power,
                                'total_rated_power' => $selection->total_rated_power,
                                'pumpable_type' => $selection->pump_type,
                            ];
                        }
                    ))->asArray(),
                ],
            ],
        ];
    }
}
