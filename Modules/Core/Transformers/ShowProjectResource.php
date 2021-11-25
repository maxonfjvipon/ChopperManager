<?php

namespace Modules\Core\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Support\Rates;

class ShowProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $rates = new Rates(Auth::user()->currency->code); // fixme

        // TODO: make selections

        return [
            'id' => $this->id,
            'name' => $this->name,
            'selections' => $this->selections()->with([
                'pump' => function ($query) {
                    $query->select('id', 'name', 'rated_power', 'series_id', 'article_num_main');
                },
                'pump.price_lists' => function($query) {
                    $query->where('country_id', Auth::user()->country_id);
                },
                'pump.price_lists.currency',
                'pump.series',
                'pump.series.discounts' => function ($query) {
                    $query->where('user_id', Auth::id());
                },

                'pump.brand',
                'pump.brand.discounts' => function ($query) {
                    $query->where('user_id', Auth::id());
                },
            ])
                ->get(['pump_id', 'selected_pump_name', 'pumps_count', 'head', 'flow', 'id', 'created_at'])
                ->map(function ($selection) use ($rates) {
                    $pump_price_list = null;
                    $pump_price = null;
                    $discounted_pump_price = null;
                    if (count($selection->pump->price_lists) === 1) {
                        $pump_price_list = $selection->pump->price_lists[0];
                        $pump_price = $pump_price_list->currency->code === $rates->base()
                            ? $pump_price_list->price
                            : round($pump_price_list->price / $rates->rate($pump_price_list->currency->code), 2);
                        $discount = count($selection->pump->series->discounts) == 1
                            ? $selection->pump->series->discounts[0]->value
                            : 0;
                        $discounted_pump_price = $pump_price - $pump_price * $discount / 100;
                    }

                    return [
                        'id' => $selection->id,
                        'pump_id' => $selection->pump->id,
                        'article_num' => $selection->pump->article_num_main,
                        'created_at' => $selection->created_at->format('d.m.Y'),
                        'flow' => $selection->flow,
                        'head' => $selection->head,
                        'selected_pump_name' => $selection->pumps_count . ' '
                            . $selection->pump->brand->name . ' '
                            . $selection->pump->series->name . ' '
                            . $selection->pump->name,
                        'discounted_price' => round($discounted_pump_price, 1) ?? null,
                        'total_discounted_price' => round($discounted_pump_price * $selection->pumps_count, 1) ?? null,
                        'rated_power' => $selection->pump->rated_power,
                        'total_rated_power' => round($selection->pump->rated_power * $selection->pumps_count, 1)
                    ];
                })->all(),
        ];
    }
}
