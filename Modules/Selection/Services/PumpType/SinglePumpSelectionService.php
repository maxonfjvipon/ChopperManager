<?php


namespace Modules\Selection\Services\PumpType;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\Pure;
use Modules\Core\Support\Rates;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Entities\SelectionRange;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;
use Modules\Selection\Http\Requests\MakeSelectionRequest;
use Modules\Selection\Support\IIntersectionPoint;
use Modules\Selection\Support\PumpPerformance\PPumpPerformance;
use Modules\Selection\Transformers\SelectionResources\SinglePumpSelectionResource;

class SinglePumpSelectionService extends PumpableTypeSelectionService
{
    /**
     * SinglePumpSelectionService constructor.
     */
    #[Pure] public function __construct()
    {
        parent::__construct(
            'selection::selection_to_export',
            'selection::selection_perf_curves'
        );
    }

    /**
     * Single pump selection create view name
     *
     * @return string
     */
    public function createPath(): string
    {
        return 'Selection::SinglePump';
    }

    /**
     * Single pump selection resource
     *
     * @param Selection $selection
     * @return JsonResource
     */
    #[Pure] public function selectionResource(Selection $selection): JsonResource
    {
        return new SinglePumpSelectionResource($selection);
    }

    protected function pumpsScope($dbPumps)
    {
        return $dbPumps->singlePumps();
    }

    /**
     * Select pumps
     *
     * @param MakeSelectionRequest $request
     * @return JsonResponse
     */
    public function select(MakeSelectionRequest $request): JsonResponse
    {
        ini_set('memory_limit', '200M');
        $dbPumps = $this->loadedPumpsFromDB($request);
        $head = $request->head;
        $flow = $request->flow;

        $reservePumpsCount = $request->reserve_pumps_count;
        $deviation = $request->deviation ?? 0;

        $rates = new Rates();
        $selectedPumps = [];
        $defaultSystemPerformance = null;
        $num = 1;
        $range = SelectionRange::find($request->range_id);

        foreach ($dbPumps as $pump) {
            $pumpPerformance = PPumpPerformance::construct($pump);
            foreach ($request->main_pumps_counts as $mainPumpsCount) {
                $qEnd = $pumpPerformance->qEnd($mainPumpsCount);
                if ($flow >= $qEnd) {
                    continue;
                }
                $coefficients = $pumpPerformance->coefficientsForPosition($mainPumpsCount);
                $qStart = $pumpPerformance->qStart($mainPumpsCount);
                $intersectionPoint = new IIntersectionPoint($coefficients, $flow, $head);

                // range length
                $qDist = $qEnd - $qStart;

                // pump with current main pumps count is appropriate
                // custom range
                if ($request->range_id === SelectionRange::$CUSTOM) {
                    $rStart = $request->custom_range[0] / 100;
                    $rEnd = (100 - $request->custom_range[1]) / 100;
                } else {
                    $rStart = $range->value;
                    $rEnd = $range->value;
                }

                if ($flow >= $qStart
                    && $flow <= $qEnd
                    && $intersectionPoint->x() >= $qStart + $qDist * $rStart
                    && $intersectionPoint->x() <= $qEnd - $qDist * $rEnd
                    && $intersectionPoint->y() >= $head + $head * ($deviation / 100)
                ) {
                    $pumpsCount = $mainPumpsCount + $reservePumpsCount;
                    $pump_price = $pump->price_list
                        ? ($pump->price_list->currency->code === $rates->base()
                            ? $pump->price_list->price
                            : round($pump->price_list->price / $rates->rate($pump->price_list->currency->code), 2))
                        : 0;
                    $pump_price_with_discount = $pump_price - $pump_price * $pump->series->discount->value / 100;

                    $selectedPumps[] = [
                        'key' => $num++,
                        'pumps_count' => $pumpsCount,
                        'name' => $pumpsCount . ' ' . $pump->brand->name . ' ' . $pump->name,
                        'pump_id' => $pump->id,
                        'articleNum' => $pump->article_num_main,
                        'retailPrice' => $pump_price,
                        'discountedPrice' => round($pump_price_with_discount, 2),
                        'retailPriceSum' => round($pump_price * $pumpsCount, 2),
                        'discountedPriceSum' => round($pump_price_with_discount * $pumpsCount, 2),
                        'dnSuction' => $pump->dn_suction->value,
                        'dnPressure' => $pump->dn_pressure->value,
                        'rated_power' => $pump->rated_power,
                        'powerSum' => round($pump->rated_power * $pumpsCount, 4),
                        'ptpLength' => $pump->ptp_length,
                        'head' => $head,
                        'flow' => $flow,
                        'main_pumps_count' => $mainPumpsCount,
                        'fluid_temperature' => $request->fluid_temperature,
                    ];
                }
            }
        }
        return $this->selectResponse($selectedPumps, $flow, $head);
    }

    /**
     * @return string[]
     */
    protected function dbPumpsGetProps(): array
    {
        return [
            'id', 'article_num_main', 'article_num_reserve', 'article_num_archive', 'series_id', 'dn_suction_id',
            'dn_pressure_id', 'name', 'rated_power', 'rated_current', 'ptp_length', 'sp_performance', 'weight',
            'image', 'sizes_image', 'cross_sectional_drawing_image', 'electric_diagram_image', 'connection_type_id',
            'fluid_temp_min', 'fluid_temp_max', 'connection_id', 'description', 'pumpable_type'
        ];
    }

    /**
     * @param MakeSelectionRequest $request
     * @return Closure
     */
    function pumpableCoefficientsClosure(MakeSelectionRequest $request): Closure
    {
        return function ($query) use ($request) {
            $query->whereBetween(
                'position',
                [1, max($request->main_pumps_counts) + $request->reserve_pumps_count]
            );
        };
    }

    /**
     * @param CurvesForSelectionRequest $request
     * @return Selection
     */
    #[Pure] protected function selectionWithParamsFromRequest(CurvesForSelectionRequest $request): Selection
    {
        $selection = new Selection;
        $selection->pump_id = $request->pump_id;
        $selection->reserve_pumps_count = $request->pumps_count - $request->main_pumps_count;
        $selection->pumps_count = $request->pumps_count;
        $selection->flow = $request->flow;
        $selection->head = $request->head;
        return $selection;
    }

    /**
     * @param ExportAtOnceSelectionRequest $request
     * @return Selection
     */
    #[Pure] protected function selectionForExportAtOnceFromRequest(ExportAtOnceSelectionRequest $request): Selection
    {
        $selection = new Selection;
        $selection->selected_pump_name = $request->selected_pump_name;
        $selection->pump_id = $request->pump_id;
        $selection->pumps_count = $request->pumps_count;
        $selection->reserve_pumps_count = $request->reserve_pumps_count;
        $selection->flow = $request->flow;
        $selection->head = $request->head;
        $selection->fluid_temperature = $request->fluid_temperature;
        return $selection;
    }
}
