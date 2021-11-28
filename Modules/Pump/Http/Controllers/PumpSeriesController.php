<?php

namespace Modules\Pump\Http\Controllers;

use App\Http\Controllers\ModuleResourceController;
use Modules\Core\Http\Requests\FilesUploadRequest;
use Modules\Core\Http\Requests\MediaUploadRequest;
use Modules\Core\Support\TenantStorage;
use Modules\Pump\Actions\ImportPumpSeriesAction;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\Pump\Http\Requests\PumpSeriesUpdateRequest;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpType;
use App\Traits\HasFilterData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Transformers\PumpSeriesPropsResource;
use Modules\Pump\Transformers\PumpSeriesResource;

class PumpSeriesController extends ModuleResourceController
{
    use HasFilterData;

    public function __construct()
    {
        parent::__construct(
            'Pump::PumpSeries/Index',
            'Pump::PumpSeries/Create',
            null,
            'Pump::PumpSeries/Edit',
        );
    }

    protected function indexFilterData(): array
    {
        return $this->asFilterData([
            'brands' => PumpBrand::pluck('name')->all(),
            'categories' => PumpCategory::pluck('name')->all(),
            'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
            'applications' => PumpApplication::pluck('name')->all(),
            'types' => PumpType::pluck('name')->all(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize('series_access');
        $this->authorize('brand_access');
        return Inertia::render($this->indexPath, [
            'filter_data' => $this->indexFilterData(),
            'brands' => PumpBrand::all(),
            'series' => PumpSeries::with(['brand', 'category', 'power_adjustment'])
                ->get()
                ->map(fn($series) => [
                    'id' => $series->id,
                    'brand' => $series->brand->name,
                    'name' => $series->name,
                    'category' => $series->category->name,
                    'power_adjustment' => $series->power_adjustment->name,
                    'applications' => $series->imploded_applications,
                    'types' => $series->imploded_types
                ])
                ->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create(): Response
    {
        $this->authorize('series_create');
        return Inertia::render($this->createPath, [
            'pump_series_props' => new PumpSeriesPropsResource(null),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PumpSeriesStoreRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(PumpSeriesStoreRequest $request): RedirectResponse
    {
        $this->authorize('series_create');
        PumpSeries::createFromRequest($request);
        return Redirect::route('pump_series.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PumpSeries $pumpSeries
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(PumpSeries $pumpSeries): Response
    {
        $this->authorize('series_edit');
        return Inertia::render($this->editPath, [
            'pump_series_props' => new PumpSeriesPropsResource(null),
            'series' => new PumpSeriesResource($pumpSeries)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PumpSeriesUpdateRequest $request
     * @param PumpSeries $pumpSeries
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(PumpSeriesUpdateRequest $request, PumpSeries $pumpSeries): RedirectResponse
    {
        $this->authorize('series_edit');
        $pumpSeries->updateFromRequest($request);
        return Redirect::route('pump_series.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PumpSeries $pumpSeries
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(PumpSeries $pumpSeries): RedirectResponse
    {
        $this->authorize('series_delete');
        $pumpSeries->delete();
        return Redirect::route('pump_series.index');
    }

    /**
     * Restore series
     *
     * @param $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('series_restore');
        PumpSeries::withTrashed()->find($id)->restore();
        return Redirect::route('pump_series.index');
    }

    /**
     * Upload series
     *
     * @param FilesUploadRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function import(FilesUploadRequest $request): RedirectResponse
    {
        $this->authorize('series_import');
        return (new ImportPumpSeriesAction($request->file('files')))->execute();
    }

    /**
     * Upload series media
     *
     * @param MediaUploadRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function importMedia(MediaUploadRequest $request): RedirectResponse
    {
        $this->authorize('series_import_media');
        $tenantStorage = new TenantStorage();
        foreach ($request->file('files') as $image)
            if (!$tenantStorage->putFileTo($request->folder, $image))
                Redirect::back()->with('error', 'Media were not imported. Please contact to administrator');
        return Redirect::back()->with('success', 'Media were imported successfully to directory: ' . $request->folder);

    }
}
