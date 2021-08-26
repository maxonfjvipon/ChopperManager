<?php

namespace App\Http\Resources;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\Selections\Single\SinglePumpSelection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        // TODO: Вынести в отдельный класс
        $rates = Currency::rates()
            ->latest()
            ->symbols(\App\Models\Currency::all()->pluck('name')->all())
            ->base('RUB')
            ->amount(1)
            ->round(5) // TODO: find optimal round
            ->get();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'selections' => SinglePumpSelection::with([
                'pump' => function ($query) {
                    $query->select('id', 'name', 'price', 'power', 'currency_id', 'series_id', 'part_num_main');
                }, 'pump.currency', 'pump.producer.discount', 'pump.series.discount'
            ])
                ->where('project_id', $this->id)
                ->where('deleted', false)
                ->get(['pump_id', 'selected_pump_name', 'pumps_count', 'pressure', 'consumption', 'id', 'created_at'])
                ->map(function ($selection) use ($rates) {
                    $pump_rub_price = $selection->pump->currency->name === 'RUB'
                        ? $selection->pump->price
                        : round($selection->pump->price / $rates[$selection->pump->currency->name], 2);

                    $pump_price = $pump_rub_price - ($selection->pump->series->discount->value
                            ? $pump_rub_price * $selection->pump->series->discount->value / 100
                            : ($selection->pump->producer->discount->value
                                ? $pump_rub_price * $selection->pump->producer->discount->value / 100
                                : 0
                            )
                        );
                    return [
                        'id' => $selection->id,
                        'created_at' => $selection->created_at,
                        'consumption' => $selection->consumption,
                        'pressure' => $selection->pressure,
                        'selected_pump_name' => $selection->pumps_count . ' '
                            . $selection->pump->producer->name . ' '
                            . $selection->pump->series->name . ' '
                            . $selection->pump->name,
                        'part_num_main' => $selection->pump->part_num_main,
                        'price' => round($pump_price, 1),
                        'sum_price' => round($pump_price * $selection->pumps_count, 1),
                        'power' => $selection->pump->power,
                        'sum_power' => $selection->pump->power * $selection->pumps_count
                    ];
                })->all(),
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
            'deleted' => false,
        ];
    }
}
