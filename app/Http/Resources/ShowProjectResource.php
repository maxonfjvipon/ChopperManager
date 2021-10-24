<?php

namespace App\Http\Resources;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\Selections\Single\SinglePumpSelection;
use App\Support\Rates;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                    $query->where('country_id', Auth::user()->id);
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
                        $discounted_pump_price = $pump_price - $pump_price * $selection->pump->series->discounts[0]->value / 100;
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
//            'selections' => SinglePumpSelection::with([
//                'pump' => function ($query) {
//                    $query->select('id', 'name', 'price', 'power', 'currency_id', 'series_id', 'part_num_main');
//                },
//                'pump.currency',
//                'pump.producer.discounts' => function ($query) {
//                    $query->where('user_id', Auth::id());
//                },
//                'pump.series.discounts' => function ($query) {
//                    $query->where('user_id', Auth::id());
//                }
//            ])
//                ->where('project_id', $this->id)
//                ->where('deleted', false)
//                ->get(['pump_id', 'selected_pump_name', 'pumps_count', 'pressure', 'consumption', 'id', 'created_at'])
//                ->map(function ($selection) use ($rates) {
//                    $pump_rub_price = $selection->pump->currency->name === 'RUB'
//                        ? $selection->pump->price
//                        : round($selection->pump->price / $rates[$selection->pump->currency->name], 2);
//
//                    $pump_price = $pump_rub_price - ($selection->pump->series->discounts[0]->value
//                            ? $pump_rub_price * $selection->pump->series->discounts[0]->value / 100
//                            : ($selection->pump->producer->discounts[0]->value
//                                ? $pump_rub_price * $selection->pump->producer->discounts[0]->value / 100
//                                : 0
//                            )
//                        );
//                    return [
//                        'id' => $selection->id,
//                        'created_at' => $selection->created_at,
//                        'consumption' => $selection->consumption,
//                        'pressure' => $selection->pressure,
//                        'selected_pump_name' => $selection->pumps_count . ' '
//                            . $selection->pump->producer->name . ' '
//                            . $selection->pump->series->name . ' '
//                            . $selection->pump->name,
//                        'part_num_main' => $selection->pump->part_num_main,
//                        'price' => round($pump_price, 1),
//                        'sum_price' => round($pump_price * $selection->pumps_count, 1),
//                        'power' => $selection->pump->power,
//                        'sum_power' => $selection->pump->power * $selection->pumps_count
//                    ];
//                })->all(),
//            'selections-2' => DB::table('single_pump_selections')
//                ->join('pumps', 'pump_id', '=', 'pumps.id')
//                ->join('currencies', 'pumps.currency_id', '=', 'currencies.id')
//                ->where('deleted', '=', false)
//                ->where('project_id', '=', $this->id)
//                ->select('price', 'power', 'created_at', 'selected_pump_name', 'pumps_count',
//                    'pumps.part_num_main', 'pressure', 'consumption', 'single_pump_selections.id', 'currencies.name as currency')
//                ->selectRaw('(price * pumps_count) as sum_price, round((power * pumps_count), 1) as sum_power')
//                ->get()->transform(function ($item) use ($rates) { // TODO: оптимизированть количество вычислений
//                    if ($item->currency !== 'RUB') {
//                        $item->sum_price = round($item->sum_price / $rates[$item->currency], 2);
//                        $item->price = round($item->price / $rates[$item->currency], 2);
//                    }
//                    return $item;
//                })->all(),
        ];
    }
}
