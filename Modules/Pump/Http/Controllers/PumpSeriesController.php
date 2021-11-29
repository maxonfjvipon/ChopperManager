<?php

namespace Modules\Pump\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Http\Requests\FilesUploadRequest;
use Modules\Core\Http\Requests\MediaUploadRequest;
use Modules\Core\Support\TenantStorage;
use Modules\Pump\Actions\ImportPumpSeriesAction;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\Pump\Http\Requests\PumpSeriesUpdateRequest;
use Modules\Pump\Entities\PumpSeries;
use App\Traits\HasFilterData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Services\PumpSeries\PumpSeriesServiceInterface;
use Modules\Pump\Transformers\PumpSeriesPropsResource;
use Modules\Pump\Transformers\PumpSeriesResource;

class PumpSeriesController extends Controller
{
    use HasFilterData;

    protected PumpSeriesServiceInterface $service;

    public function __construct(PumpSeriesServiceInterface $service)
    {
        $this->service = $service;
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
        return $this->service->__index();
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
        return Inertia::render($this->service->createPath(), [
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
        return $this->service->__store($request);
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
        return Inertia::render($this->service->editPath(), [
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
