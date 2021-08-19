<?php

namespace App\Http\Controllers;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Http\Requests\MakeSelectionRequest;
use App\Http\Requests\StoreSelectionRequest;
use App\Http\Requests\UpdateSelectionRequest;
use App\Http\Resources\SinglePumpSelectionResource;
use App\Models\DN;
use App\Models\LimitCondition;
use App\Models\Selections\Single\SinglePumpSelection;
use App\Support\Selections\IntersectionPoint;
use App\Support\Selections\Regression;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class SelectionsController extends SelectionsHelperController
{
    /**
     * Show selections dashboard page
     *
     * @param $project_id
     * @return Response
     */
    public function dashboard($project_id): Response
    {
        return Inertia::render('Selections/Dashboard', ['project_id' => $project_id]);
    }

    /**
     * Show create selection form
     *
     * @param $project_id
     * @return Response
     */
    public function create($project_id): Response
    {
        return Inertia::render('Selections/Single', $this->inertiaRenderSingleProps($project_id));
    }

    /**
     * Display selection
     *
     * @param SinglePumpSelection $selection
     * @return Response
     */
    public function show(SinglePumpSelection $selection): Response
    {
        return Inertia::render('Selections/Single', array_merge(
                $this->inertiaRenderSingleProps($selection->project_id),
                ['selection' => new SinglePumpSelectionResource($selection)]
            )
        );
    }

    public function destroy(SinglePumpSelection $selection)
    {
        $selection->update(['deleted' => true]);
        return Redirect::back()->with('success', 'Подбор успешно удален');
    }

    /**
     * Fake redirect from '/selections/select/{project}' to '/selections/create/{project}'
     *
     * @param $project_id
     * @return RedirectResponse
     */
    // FIXME: get rid of fake path
    public function fake($project_id): RedirectResponse
    {
        return Redirect::route('selections.create', $project_id);
    }

    /**
     * Create selection with data
     *
     * @param StoreSelectionRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSelectionRequest $request): RedirectResponse
    {
        SinglePumpSelection::create($request->validated());
        return Redirect::route('projects.show', $request->project_id);
    }

    /**
     * Update selection with data
     *
     * @param UpdateSelectionRequest $request
     * @param SinglePumpSelection $selection
     * @return RedirectResponse
     */
    public function update(UpdateSelectionRequest $request, SinglePumpSelection $selection): RedirectResponse
    {
        $selection->update($request->validated());
        return Redirect::route('projects.show', $request->project_id);
    }

    /**
     * Select pumps
     *
     * @param MakeSelectionRequest $request
     * @param $project_id
     */
    // TODO: переделать на Eloquent (???)
    public function select(MakeSelectionRequest $request)
    {
        $validated = $request->validated();

        // pumps by series
        $dbPumps = DB::table('pumps')->whereIn('series_id', $validated['series_ids']);

        // phases
        if ($validated['current_phase_ids'] && count($validated['current_phase_ids']) > 0) {
            $dbPumps = $dbPumps->whereIn('phase_id', $validated['current_phase_ids']);
        }

        // connection types
        if ($validated['connection_type_ids'] && count($validated['connection_type_ids']) > 0) {
            $dbPumps = $dbPumps->whereIn('connection_type_id', $validated['connection_type_ids']);
        }

        // dn input && dn output
        $dnInputLimit = false;
        $dnOutputLimit = false;

        if ($validated['dn_input_limit_checked']
            && $validated['dn_input_limit_condition_id']
            && $validated['dn_input_limit_id']
        ) {
            $dnInputLimit = true;
            $dbPumps = $dbPumps->join('dns as dns1', function ($join) use ($validated) {
                $join
                    ->on('pumps.dn_input_id', '=', 'dns1.id')
                    ->where(
                        'dns1.value',
                        LimitCondition::find($validated['dn_input_limit_condition_id'])->value,
                        DN::find($validated['dn_input_limit_id'])->value
                    );
            });
        }

        if ($validated['dn_output_limit_checked']
            && $validated['dn_output_limit_condition_id']
            && $validated['dn_output_limit_id']
        ) {
            $dnOutputLimit = true;
            $dbPumps = $dbPumps->join('dns as dns2', function ($join) use ($validated) {
                $join
                    ->on('pumps.dn_output_id', '=', 'dns2.id')
                    ->where(
                        'dns2.value',
                        LimitCondition::find($validated['dn_output_limit_condition_id'])->value,
                        DN::find($validated['dn_output_limit_id'])->value
                    );
            });
        }

        // DNs
        if (!$dnInputLimit) {
            $dbPumps = $dbPumps->join('dns as dns1', 'pumps.dn_input_id', '=', 'dns1.id');
        }
        if (!$dnOutputLimit) {
            $dbPumps = $dbPumps->join('dns as dns2', 'pumps.dn_output_id', '=', 'dns2.id');
        }

        // POWER LIMIT
        if ($validated['power_limit_checked']
            && $validated['power_limit_condition_id']
            && $validated['power_limit_value']
        ) {
            $dbPumps = $dbPumps
                ->where('power', LimitCondition::find($validated['power_limit_condition_id'])->value, $validated['power_limit_value']);
        }

        // BETWEEN AXES DISTANCE LIMIT
        if ($validated['between_axes_limit_checked']
            && $validated['between_axes_limit_condition_id']
            && $validated['between_axes_limit_value']
        ) {
            $dbPumps = $dbPumps->where(
                'between_axes_dist',
                LimitCondition::find($validated['between_axes_limit_condition_id'])->value,
                $validated['between_axes_limit_value']
            );
        }

        $dbPumps = $dbPumps
            ->join('pump_series', 'pumps.series_id', '=', 'pump_series.id')
            ->join('currencies', 'pumps.currency_id', '=', 'currencies.id')
            ->join('pump_producers', 'pump_series.producer_id', '=', 'pump_producers.id')
            ->select(
                'pumps.id', 'part_num_main', 'pumps.name', 'price', 'between_axes_dist', 'power',
                'performance', 'pump_producers.name as producer', 'pump_series.name as series',
                'currencies.name as currency', 'dns1.value as dnInput', 'dns2.value as dnOutput')
            ->get();

        if (count($dbPumps) === 0) {
            return response()->json(['info' => 'Насосов не найдено'], 500);
        }

        $pressure = $validated['pressure'];
        $consumption = $validated['consumption'];
        $backupPumpsCount = $validated['backup_pumps_count'];

        $appropriatedPumpsAndCounts = $this->appropriatedPumpsAndCounts(
            $dbPumps,
            $validated['main_pumps_counts'],
            $pressure,
            $consumption,
            $validated['limit'] ?? 0
        );

        if (count($appropriatedPumpsAndCounts) === 0) {
            return response()->json(['info' => 'Насосов не найдено'], 500);
        }

        $selectedPumps = [];
        $num = 1;

        $rates = Currency::rates()
            ->latest()
            ->symbols(\App\Models\Currency::all()->pluck('name')->all())
            ->base('RUB')
            ->amount(1)
            ->round(5) // TODO: find optimal round
            ->get();

        // main cycle
        foreach ($appropriatedPumpsAndCounts as $appropriatedPumpAndCount) {
            $pump = $appropriatedPumpAndCount['pump'];
            $mainPumpsCount = $appropriatedPumpAndCount['count'];
            $pumpsCount = $mainPumpsCount + $backupPumpsCount;

            $arrayPerformance = $this->arrayPerformance(" ", $pump->performance);

            $yMax = 0;
            $lines = array();
            $intersectionPoint = [];
            $systemPerformance = array();

            // calc pumpLines, intersection point, system performance and y max
            for ($j = 1; $j <= $pumpsCount; $j++) {
                $data = $this->lineData($arrayPerformance, $j);
                $xx = array_map(function ($value) {
                    return $value[0];
                }, $data);

                $regression = (new Regression($data))->polynomial();
                if ($j == $mainPumpsCount) {
                    $intersectionPoint = new IntersectionPoint($regression->coefficients(), $pressure, $consumption);
                    $systemPerformance = $this->systemPerformance($intersectionPoint, $pressure, $consumption);
                }
                $pumpLine = $this->pumpLine($xx, $regression);
                $lines[] = $pumpLine['line'];
                if ($pumpLine['yMax'] > $yMax) {
                    $yMax = $pumpLine['yMax'];
                }
            }

            $pump_rub_price = $pump->currency === 'RUB'
                ? $pump->price
                : round($pump->price / $rates[$pump->currency], 2);

            // todo: price and currencies
            $selectedPumps[] = [
                'key' => $num++,
                'pump_id' => $pump->id,
                'pumps_count' => $pumpsCount,
                'name' => $pumpsCount . ' ' . $pump->producer . ' ' . $pump->series . ' ' . $pump->name,
                'partNum' => $pump->part_num_main,
                'retailPrice' => $pump_rub_price,
                'personalPrice' => $pump_rub_price,
                'retailPriceSum' => round($pump_rub_price * $pumpsCount, 2),
                'personalPriceSum' => round($pump_rub_price * $pumpsCount, 2),
                'dnInput' => $pump->dnInput,
                'dnOutput' => $pump->dnOutput,
                'power' => $pump->power,
                'powerSum' => round($pump->power * $pumpsCount, 1),
                'betweenAxesDist' => $pump->between_axes_dist,
                'intersectionPoint' => [
                    'x' => $intersectionPoint->x(),
                    'y' => $intersectionPoint->y()
                ],
                'systemPerformance' => $systemPerformance,
                'yMax' => $yMax,
                'lines' => $lines
            ];
        }

        return response()->json([
            'selected_pumps' => $selectedPumps,
            'working_point' => [
                'x' => $consumption,
                'y' => $pressure
            ]
        ]);
    }
}
