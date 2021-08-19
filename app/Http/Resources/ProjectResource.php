<?php

namespace App\Http\Resources;

use AmrShawky\LaravelCurrency\Facade\Currency;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
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
            'selections' => DB::table('single_pump_selections')
                ->join('pumps', 'pump_id', '=', 'pumps.id')
                ->join('currencies', 'pumps.currency_id', '=', 'currencies.id')
                ->where('deleted', '=', false)
                ->select('price', 'power', 'created_at', 'selected_pump_name', 'pumps_count',
                    'pumps.part_num_main', 'pressure', 'consumption', 'single_pump_selections.id', 'currencies.name as currency')
                ->selectRaw('(price * pumps_count) as sum_price, round((power * pumps_count), 1) as sum_power')
                ->get()->transform(function ($item) use ($rates) { // TODO: оптимизированть количество вычислений
                    if ($item->currency !== 'RUB') {
                        $item->sum_price = round($item->sum_price / $rates[$item->currency], 2);
                        $item->price = round($item->price / $rates[$item->currency], 2);
                    }
                    return $item;
                })->all(),
            'deleted' => false,
        ];
    }
}
