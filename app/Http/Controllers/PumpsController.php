<?php

namespace App\Http\Controllers;

use App\Actions\ImportPumpsAction;
use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\PumpResource;
use App\Models\ConnectionType;
use App\Models\DN;
use App\Models\MainsConnection;
use App\Models\Pumps\ElPowerAdjustment;
use App\Models\Pumps\Pump;
use App\Models\Pumps\PumpApplication;
use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\PumpCategory;
use App\Models\Pumps\PumpSeries;
use App\Models\Pumps\PumpType;
use App\Traits\HasFilterData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PumpsController extends Controller
{
    use HasFilterData;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Pumps/Index', [
            'pumps' => Inertia::lazy(fn() => Pump::with([
                'series',
                'series.brand',
                'series.power_adjustment',
                'series.applications',
                'series.types'
            ])
                ->with('category')
                ->with('connection')
                ->with('dn_suction')
                ->with('dn_pressure')
                ->with('connection_type')
                ->with('currency:id,code')
                ->get()
                ->map(fn($pump) => [
                    'id' => $pump->id,
                    'article_num_main' => $pump->article_num_main,
                    'article_num_reserve' => $pump->article_num_reserve,
                    'article_num_archive' => $pump->article_num_archive,
                    'brand' => $pump->series->brand->name,
                    'series' => $pump->series->name,
                    'name' => $pump->name,
                    'weight' => $pump->weight,
                    'price' => $pump->price,
                    'currency' => $pump->currency->code,
                    'rated_power' => $pump->rated_power,
                    'rated_current' => $pump->rated_current,
                    'connection_type' => $pump->connection_type->name,
                    'fluid_temp_min' => $pump->fluid_temp_min,
                    'fluid_temp_max' => $pump->fluid_temp_max,
                    'ptp_length' => $pump->ptp_length,
                    'dn_suction' => $pump->dn_suction->value,
                    'dn_pressure' => $pump->dn_pressure->value,
                    'category' => $pump->category->name,
                    'power_adjustment' => $pump->series->power_adjustment->name,
                    'mains_connection' => $pump->connection->full_value,
                    'applications' => $pump->applications,
                    'types' => $pump->types,
                ])),
            'filter_data' => $this->asFilterData([
                'brands' => PumpBrand::pluck('name')->all(),
                'series' => PumpSeries::pluck('name')->all(),
//                'brands_series' => PumpBrand::with('series')->get()->all(),
                'categories' => PumpCategory::pluck('name')->all(),
                'connections' => ConnectionType::pluck('name')->all(),
                'dns' => DN::pluck('value')->all(),
                'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
                'mains_connections' => MainsConnection::all()->map(fn($mc) => $mc->full_value)->toArray(),
                'types' => PumpType::pluck('name')->all(),
                'applications' => PumpApplication::pluck('name')->all(),
            ])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Pump $pump
     * @return Response
     */
    public function show(Pump $pump): Response
    {
        return Inertia::render('Pumps/Show', [
            'pump' => new PumpResource($pump),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function import(FileUploadRequest $request): RedirectResponse
    {
         return (new ImportPumpsAction())->execute($request->file);
    }

}
