<?php

namespace Modules\Pump\Http\Controllers;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Requests\FilesUploadRequest;
use Modules\Core\Http\Requests\MediaUploadRequest;
use Modules\Core\Support\TenantStorage;
use Modules\Pump\Actions\ImportPumpsAction;
use Modules\Pump\Actions\ImportPumpsPriceListsAction;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpType;
use App\Traits\HasFilterData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Transformers\PumpResource;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class PumpsController extends Controller
{
    use HasFilterData, UsesTenantModel;

    protected function pumpFilterData(): array
    {
        return $this->asFilterData([
            'brands' => PumpBrand::pluck('name')->all(),
            'series' => PumpSeries::pluck('name')->all(),
            'categories' => PumpCategory::pluck('name')->all(),
            'connections' => ConnectionType::pluck('name')->all(),
            'dns' => DN::pluck('value')->all(),
            'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
            'mains_connections' => MainsConnection::all()->map(fn($mc) => $mc->full_value)->toArray(),
            'types' => PumpType::pluck('name')->all(),
            'applications' => PumpApplication::pluck('name')->all(),
        ]);
    }

    protected function lazyLoadedPumps($pumps = null): Closure
    {
        return fn() => ($pumps ?: new Pump)->with([
            'series',
            'series.brand',
            'series.power_adjustment',
            'series.category',
            'series.applications',
            'series.types'
        ])
            ->with('connection')
            ->with('dn_suction')
            ->with('dn_pressure')
            ->with('connection_type')
            ->with(['price_lists' => function ($query) {
                $query->where('country_id', Auth::user()->country_id);
            }, 'price_lists.currency'])
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
                'price' => $pump->price_lists[0]->price ?? null,
                'currency' => $pump->price_lists[0]->currency->code ?? null,
                'rated_power' => $pump->rated_power,
                'rated_current' => $pump->rated_current,
                'connection_type' => $pump->connection_type->name,
                'fluid_temp_min' => $pump->fluid_temp_min,
                'fluid_temp_max' => $pump->fluid_temp_max,
                'ptp_length' => $pump->ptp_length,
                'dn_suction' => $pump->dn_suction->value,
                'dn_pressure' => $pump->dn_pressure->value,
                'category' => $pump->series->category->name,
                'power_adjustment' => $pump->series->power_adjustment->name,
                'mains_connection' => $pump->connection->full_value,
                'applications' => $pump->applications,
                'types' => $pump->types,
            ])->all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize('pump_access');
        return Inertia::render('Pump::Pumps/Index', [
            'pumps' => Inertia::lazy($this->lazyLoadedPumps()),
            'filter_data' => $this->pumpFilterData()
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
     * @param Request $request
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
     * @throws AuthorizationException
     */
    public function show(Pump $pump): Response
    {
        $this->authorize('pump_show');
        return Inertia::render('Pump::Pumps/Show', [
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
     * @param Request $request
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

    /**
     * Import
     *
     * @param FilesUploadRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function import(FilesUploadRequest $request): RedirectResponse
    {
        $this->authorize('pump_import');
        return (new ImportPumpsAction($request->file('files')))->execute();
    }

    /**
     * Import price lists
     *
     * @param FilesUploadRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function importPriceLists(FilesUploadRequest $request): RedirectResponse
    {
        $this->authorize('price_list_import');
        return (new ImportPumpsPriceListsAction($request->file('files')))->execute();
    }

    /**
     * Import media
     *
     * @param MediaUploadRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function importMedia(MediaUploadRequest $request): RedirectResponse
    {
        $this->authorize('pump_import_media');
        $tenantStorage = new TenantStorage();
        foreach ($request->file('files') as $image)
            if (!$tenantStorage->putFileTo($request->folder, $image))
                Redirect::back()->with('error', 'Media were not imported. Please contact to administrator');
        return Redirect::back()->with('success', 'Media were imported successfully to directory: ' . $request->folder);
    }

}
