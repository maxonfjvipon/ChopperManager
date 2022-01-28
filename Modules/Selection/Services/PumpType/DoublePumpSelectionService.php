<?php


namespace Modules\Selection\Services\PumpType;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\Pure;
use Modules\Core\Support\Rates;
use Modules\Core\Support\TenantStorage;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Entities\SelectionRange;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;
use Modules\Selection\Http\Requests\MakeSelectionRequest;
use Modules\Selection\Support\IIntersectionPoint;
use Modules\Selection\Support\PumpPerformance\PPumpPerformance;
use Modules\Selection\Transformers\SelectionResources\DoublePumpSelectionResource;

class DoublePumpSelectionService extends PumpableTypeSelectionService
{
    /**
     * DoublePumpSelectionService constructor.
     */
    #[Pure] public function __construct()
    {
        parent::__construct(
            'selection::selection_to_export',
            'selection::selection_perf_curves'
        );
    }

    /**
     * @return string
     */
    public function createPath(): string
    {
        return 'Selection::DoublePump';
    }

    /**
     * @param Selection $selection
     * @return JsonResource
     */
    #[Pure] public function selectionResource(Selection $selection): JsonResource
    {
        return new DoublePumpSelectionResource($selection);
    }

    /**
     * @param MakeSelectionRequest $request
     * @return JsonResponse
     */
    public function select(MakeSelectionRequest $request): JsonResponse
    {
        ini_set('memory_limit', '200M');
        $dbPumps = $this->loadedPumpsFromDB($request);
        $head = $request->head;
        $flow = $request->flow;
        $deviation = $request->deviation ?? 0;
        $range = SelectionRange::find($request->range_id);

        $rates = new Rates();
        $selectedPumps = [];
        $num = 1;
        $tenantStorage = new TenantStorage();
        $perfLinePos = $request->dp_work_scheme_id;

        foreach ($dbPumps as $pump) {
            $pumpPerformance = PPumpPerformance::construct($pump);
            $qEnd = $pumpPerformance->qEnd($perfLinePos);
            if ($flow >= $qEnd) {
                continue;
            }
            $coefficients = $pumpPerformance->coefficientsForPosition($perfLinePos);
            $qStart = $pumpPerformance->qStart($perfLinePos);
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
                $pump_price = $pump->price_list
                    ? ($pump->price_list->currency->code === $rates->base()
                        ? $pump->price_list->price
                        : round($pump->price_list->price / $rates->rate($pump->price_list->currency->code), 2))
                    : 0;
                $pump_price_with_discount = $pump_price - $pump_price * $pump->series->discount->value / 100;

                $selectedPumps[] = [
                    'key' => $num++,
                    'name' => $pump->brand->name . ' ' . $pump->name,
                    'pump_id' => $pump->id,
                    'articleNum' => $pump->article_num_main,
                    'retailPrice' => $pump_price,
                    'discountedPrice' => round($pump_price_with_discount, 2),
                    'retailPriceSum' => $pump_price,
                    'discountedPriceSum' => round($pump_price_with_discount, 2),
                    'dnSuction' => $pump->dn_suction->value,
                    'dnPressure' => $pump->dn_pressure->value,
                    'rated_power' => $pump->rated_power * 2,
                    'powerSum' => $pump->rated_power * 2,
                    'ptpLength' => $pump->ptp_length,
                    'head' => $head,
                    'flow' => $flow,
                    'fluid_temperature' => $request->fluid_temperature,
                    'dp_work_scheme_id' => $request->dp_work_scheme_id,
                ];
            }
        }
        return $this->selectResponse($selectedPumps, $flow, $head);
    }

    /**
     * @return array
     */
    protected function dbPumpsGetProps(): array
    {
        return [
            'id', 'article_num_main', 'article_num_reserve', 'article_num_archive', 'series_id', 'dn_suction_id',
            'dn_pressure_id', 'name', 'rated_power', 'rated_current', 'ptp_length', 'dp_peak_performance',
            'dp_standby_performance', 'weight', 'image', 'sizes_image', 'cross_sectional_drawing_image', 'electric_diagram_image',
            'connection_type_id', 'fluid_temp_min', 'fluid_temp_max', 'connection_id', 'description', 'pumpable_type'
        ];
    }

    /**
     * @param MakeSelectionRequest $request
     * @return Closure
     */
    function pumpableCoefficientsClosure(MakeSelectionRequest $request): Closure
    {
        return function ($query) {
            $query->whereIn('position', [1, 2]);
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
        $selection->flow = $request->flow;
        $selection->head = $request->head;
        $selection->dp_work_scheme_id = $request->dp_work_scheme_id;
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
        $selection->flow = $request->flow;
        $selection->head = $request->head;
        $selection->fluid_temperature = $request->fluid_temperature;
        $selection->dp_work_scheme_id = $request->dp_work_scheme_id;
        return $selection;
    }

    protected function pumpsScope($dbPumps)
    {
        return $dbPumps->doublePumps();
    }
}
