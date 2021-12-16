<?php

namespace Modules\Selection\Services\PumpType;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Support\TenantStorage;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Contracts\ManageSelectionContract;
use Modules\Selection\Contracts\PumpableTypeSelectionContract;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;
use Modules\Selection\Http\Requests\ExportSelectionRequest;
use Modules\Selection\Http\Requests\MakeSelectionRequest;
use Modules\Selection\Traits\ConstructsSelectionCurvesFromRequest;
use VerumConsilium\Browsershot\Facades\PDF;

abstract class PumpableTypeSelectionService implements PumpableTypeSelectionContract, ManageSelectionContract
{
    use ConstructsSelectionCurvesFromRequest;

    protected string $exportSelectionViewName;
    protected string $selectionCurvesViewName;

    /**
     * PumpableTypeSelectionService constructor.
     * @param string $exportSelectionViewName
     * @param string $selectionCurvesViewName
     */
    public function __construct(string $exportSelectionViewName, string $selectionCurvesViewName)
    {
        $this->exportSelectionViewName = $exportSelectionViewName;
        $this->selectionCurvesViewName = $selectionCurvesViewName;
    }

    /**
     * @return array
     */
    abstract protected function dbPumpsGetProps(): array;

    /**
     * @param MakeSelectionRequest $request
     * @return Closure
     */
    abstract protected function pumpableCoefficientsClosure(MakeSelectionRequest $request): Closure;

    abstract protected function pumpsScope($dbPumps);

    /**
     * @param MakeSelectionRequest $request
     * @return mixed
     */
    protected function loadedPumpsFromDB(MakeSelectionRequest $request)
    {
        $dbPumps = Pump::whereIn('series_id', $request->series_ids)
            ->with([
                'series',
                'series.discount',
                'series.types',
                'series.category',
                'series.applications',
                'series.power_adjustment',
                'price_list',
                'price_list.currency',
                'brand',
                'connection_type',
                'mains_connection',
                'files',
                'coefficients' => $this->pumpableCoefficientsClosure($request)
            ]);

        $dbPumps = $this->pumpsScope($dbPumps);

        // DNS
        $dnSuctionLimit = false;
        $dnPressureLimit = false;

        // ADDITIONAL FILTERS
        if ($request->use_additional_filters) {
            // MAINS CONNECTIONS
            if ($request->mains_connection_ids && count($request->mains_connection_ids) > 0) {
                $dbPumps = $dbPumps->whereIn('connection_id', $request->mains_connection_ids);
            }

            // CONNECTION TYPES
            if ($request->connection_type_ids && count($request->connection_type_ids) > 0) {
                $dbPumps = $dbPumps->whereIn('connection_type_id', $request->connection_type_ids);
            }

            // DN SUCTION LIMITING
            if ($request->dn_suction_limit_checked
                && $request->dn_suction_limit_condition_id
                && $request->dn_suction_limit_id
            ) {
                $dnSuctionLimit = true;
                $dbPumps = $dbPumps->whereRelation('dn_suction',
                    'id',
                    LimitCondition::find($request->dn_suction_limit_condition_id)->value,
                    $request->dn_suction_limit_id
                );
            }

            // DN PRESSURE LIMITING
            if ($request->dn_pressure_limit_checked
                && $request->dn_pressure_limit_condition_id
                && $request->dn_pressure_limit_id
            ) {
                $dnPressureLimit = true;
                $dbPumps = $dbPumps->whereRelation('dn_pressure',
                    'id',
                    LimitCondition::find($request->dn_pressure_limit_condition_id)->value,
                    $request->dn_pressure_limit_id
                );
            }

            // POWER LIMITING
            if ($request->power_limit_checked
                && $request->power_limit_condition_id
                && $request->power_limit_value
            ) {
                $dbPumps = $dbPumps->where(
                    'rated_power',
                    LimitCondition::find($request->power_limit_condition_id)->value,
                    $request->power_limit_value
                );
            }

            // PTP LENGTH DISTANCE LIMITING
            if ($request->ptp_length_limit_checked
                && $request->ptp_length_limit_condition_id
                && $request->ptp_length_limit_value
            ) {
                $dbPumps = $dbPumps->where(
                    'ptp_length',
                    LimitCondition::find($request->ptp_length_limit_condition_id)->value,
                    $request->ptp_length_limit_value
                );
            }
        }

        // DNS
        if (!$dnSuctionLimit) {
            $dbPumps = $dbPumps->with('dn_suction');
        }
        if (!$dnPressureLimit) {
            $dbPumps = $dbPumps->with('dn_pressure');
        }

        return $dbPumps->get($this->dbPumpsGetProps());
    }

    /**
     * @param $pump
     * @param TenantStorage $storage
     * @return array[]
     */
    protected function pumpInfo($pump, TenantStorage $storage): array
    {
        return [
            'pump_info' => [
                'article_num_main' => $pump->article_num_main, //
                'article_num_reserve' => $pump->article_num_reserve, //
                'article_num_archive' => $pump->article_num_archive, //
                'full_name' => $pump->full_name, //
                'weight' => $pump->weight, //
                'rated_power' => $pump->rated_power, //
                'rated_current' => $pump->rated_current, //
                'connection_type' => $pump->connection_type->name, //
                'fluid_temp_min' => $pump->fluid_temp_min, //
                'fluid_temp_max' => $pump->fluid_temp_max, //
                'ptp_length' => $pump->ptp_length, //
                'dn_suction' => $pump->dn_suction->value, //
                'dn_pressure' => $pump->dn_pressure->value, //
                'category' => $pump->series->category->name, //
                'power_adjustment' => $pump->series->power_adjustment->name, //
                'connection' => $pump->mains_connection->full_value, //
                'applications' => $pump->applications, //
                'types' => $pump->types, //
                'description' => $pump->description,
                'images' => [
                    'pump' => $storage->urlToImage($pump->image),
                    'sizes' => $storage->urlToImage($pump->sizes_image),
                    'electric_diagram' => $storage->urlToImage($pump->electric_diagram_image),
                    'cross_sectional_drawing' => $storage->urlToImage($pump->cross_sectional_drawing_image),
                ],
                'files' => $pump->files
                    ->map(fn($file) => $storage->urlToFile($file->file_name))
                    ->filter(fn($file) => $file != null)
                    ->map(fn($file) => [
                        'name' => basename($file),
                        'link' => $file
                    ])
            ],
        ];
    }

    /**
     * @param $selectedPumps
     * @param $flow
     * @param $head
     * @return JsonResponse
     */
    protected function selectResponse($selectedPumps, $flow, $head): JsonResponse
    {
        if (count($selectedPumps) === 0) {
            return response()->json(['info' => __('flash.selections.pumps_not_found')]);
        }
        return response()->json([
            'selected_pumps' => $selectedPumps,
            'working_point' => [
                'x' => $flow,
                'y' => $head
            ],
        ]);
    }

    /**
     * @param ExportSelectionRequest $request
     * @param Selection $selection
     * @return Response
     */
    public function export(ExportSelectionRequest $request, Selection $selection): Response
    {
        $selection->load([
            'pump',
            'pump.connection_type',
            'pump.series',
            'pump.series.category',
            'pump.series.power_adjustment',
            'pump.brand'
        ]);
        $selection->{'curves_data'} = $this->selectionCurvesData($selection);
        return PDF::loadHtml(view($this->exportSelectionViewName, [
            'selection' => $selection,
            'request' => $request,
        ])->render())->download();
    }

    /**
     * @param ExportAtOnceSelectionRequest $request
     * @return Selection
     */
    abstract protected function selectionForExportAtOnceFromRequest(ExportAtOnceSelectionRequest $request): Selection;

    /**
     * @param ExportAtOnceSelectionRequest $request
     * @return Response
     */
    public function exportAtOnce(ExportAtOnceSelectionRequest $request): Response
    {
        return $this->export($request, $this->selectionForExportAtOnceFromRequest($request));
    }

    /**
     * @param CurvesForSelectionRequest $request
     * @return JsonResponse
     */
    public function curves(CurvesForSelectionRequest $request): JsonResponse
    {
        return response()->json(
            view(
                $this->selectionCurvesViewName,
                $this->selectionCurvesDataFromRequest($request)
            )->render()
        );
    }
}
