<?php

use App\Interfaces\Rates;
use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\Elegant_Elephant\Numerable\NumSticky;
use Maxonfjvipon\Elegant_Elephant\Numerable\Rounded;
use Maxonfjvipon\Elegant_Elephant\Text\TxtSticky;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Support\ArrCostStructure;
use Modules\Selection\Support\Performance\PpQStart;
use Modules\Selection\Support\Point\IntersectionPoint;
use Modules\Selection\Support\PumpStationPrice;
use Modules\Selection\Support\Regression\EqFromPumpCoefficients;
use Modules\Selection\Support\TxtCostStructure;
use Modules\Selection\Support\TxtPumpStationName;

if (!function_exists('pumpsForSelecting')) {
    function pumpsForSelecting(RqMakeSelection $request): array
    {
        return Pump::whereIn('series_id', $request->pump_series_ids)
            ->whereIsDiscontinued(false)
            ->with([
                'series',
                'series.brand',
                'coefficients' => fn ($query) => $query->whereBetween(
                    'position',
                    [1, max($request->main_pumps_counts) + $request->reserve_pumps_count]
                ),
            ])
            ->get()
            ->all();
    }
}

if (!function_exists('pumpIsGoodForSelect')) {
    /**
     * @throws Exception
     */
    function pumpIsGoodForSelect(RqMakeSelection $request, Pump $pump, int $mainPumpsCount, Numerable $qEnd): bool
    {
        $qStart = new NumSticky(
            new PpQStart(
                $pump->performance(),
                $mainPumpsCount
            )
        );
        $intersectionPoint = new IntersectionPoint(
            new EqFromPumpCoefficients(
                $pump->coefficientsAt($mainPumpsCount)
            ),
            $request->flow,
            $request->head
        );

        return $request->flow >= $qStart->asNumber()
            && $intersectionPoint->x() >= $qStart->asNumber()
            && $intersectionPoint->x() <= $qEnd->asNumber()
            && $intersectionPoint->y() >= $request->head + $request->head * (($request->deviation ?? 0) / 100);
    }
}

if (!function_exists('collectorsForAutoSelection')) {
    /**
     * @throws Exception
     */
    function collectorsForAutoSelection(RqMakeSelection $request, Arrayable $dnsMaterials, Pump $pump, int $pumpsCount, int $minDn): array
    {
        return array_map(
            fn (array $dnMaterial) => Collector::forSelection(
                $request->station_type,
                $pump,
                $pumpsCount,
                $dnMaterial,
                $minDn,
            ),
            $dnsMaterials->asArray()
        );
    }
}

if (!function_exists('controlSystemsForSelection')) {
    /**
     * @throws Exception
     */
    function controlSystemsForSelection(RqMakeSelection $request, Pump $pump, int $pumpsCount, ?bool $isSprinkler = false, ?Collection $controlSystems = null): array
    {
        return array_map(
            fn (int $controlSystemTypeId) => ($controlSystems ?? ControlSystem::allOrCached())
                ->where('power', '>=', $pump->power)
                ->where('pumps_count', $pumpsCount)
                ->where('type_id', $controlSystemTypeId)
                ->when(
                    $request->station_type === StationType::getKey(StationType::AF),
                    fn ($query) => $query
                        ->where('avr.value', $request->boolean('avr'))
                        ->where('gate_valves_count', $request->gate_valves_count)
                        ->where('kkv.value', $request->boolean('kkv'))
                        ->where('on_street.value', $request->boolean('on_street'))
                        ->where('has_jockey.value', $isSprinkler)
                )
                ->sortBy('power')
                ->first(),
            $request->control_system_type_ids,
        );
    }
}

if (!function_exists('selectedPump')) {
    /**
     * @throws Exception
     */
    function selectedPump(int &$key, RqMakeSelection $request, int $mainPumpsCount, Rates $rates, array $components): array
    {
        return array_merge(
            [
                'key' => $key++,
                'created_at' => formatted_date(now()),
                'name' => $name = (new TxtSticky(
                    new TxtPumpStationName(
                        $controlSystem = $components['control_system'],
                        $pumpsCount = $mainPumpsCount + $request->reserve_pumps_count,
                        $pump = $components['pump'],
                        $inputCollector = ($collectors = $components['collectors'])->firstWhere('dn_pipes', $pump->dn_suction),
                        $components['jockey_pump'] ?? null
                    )
                ))->asString(),
                'cost_price' => (new Rounded(
                    new PumpStationPrice(
                        $costStructure = new ArrSticky(
                            new ArrCostStructure(
                                $request,
                                $rates,
                                $components,
                                $pumpsCount
                            )
                        )
                    ),
                    2
                ))->asNumber(),
                'cost_structure' => (new TxtCostStructure($costStructure))->asString(),
                'pump_article' => $pump->article,
                'power' => $pump->power,
                'total_power' => $pump->power * $pumpsCount,
                'control_system_article' => $controlSystem?->article,

                'control_system_id' => $controlSystem?->id,
                'input_collector_id' => $inputCollector?->id,
                'output_collector_id' => $collectors->firstWhere('dn_pipes', $pump->dn_pressure)?->id,
                'chassis_id' => $components['chassis']?->id,
                'pump_id' => $pump->id,

                'flow' => $request->flow,
                'head' => $request->head,
                'reserve_pumps_count' => $request->reserve_pumps_count,
                'main_pumps_count' => $mainPumpsCount,
                'bad' => str_contains($name, '?'),
            ]
        );
    }
}
