<?php

namespace Modules\Core\Transformers;

use App\Support\FormattedPrice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Core\Support\Rates;
use Modules\Selection\Entities\Selection;

class ShowProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function toArray($request): array
    {
        $rates = Rates::new(); // fixme

        // TODO: make selections
        return [
            'id' => $this->id,
            'name' => $this->name,
            'selections' => ArrMapped::new(
                [...Selection::withOrWithoutTrashed()
                    ->whereProjectId($this->id)
                    ->with([
                        'pump' => function ($query) {
                            $query->select('id', 'name', 'rated_power', 'series_id', 'article_num_main', 'pumpable_type');
                        },
                        'pump.price_list',
                        'pump.price_list.currency',
                        'pump.series',
                        'pump.series.auth_discount',
                        'pump.brand',
                        'pump.brand.discount'
                    ])
                    ->get(['pump_id', 'selected_pump_name', 'pumps_count', 'head', 'flow', 'id', 'created_at'])],
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
                        'discounted_price' => $selection->discounted_price,
                        'total_discounted_price' => $selection->total_discounted_price,
                        'rated_power' => $selection->pump_rated_power,
                        'total_rated_power' => $selection->total_rated_power,
                        'pumpable_type' => $selection->pump_type
                    ];
                }
            )->asArray()
        ];
    }
}
