<?php

namespace Modules\Pump\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redirect;
use Modules\Core\Http\Requests\FilesUploadRequest;
use Modules\Core\Http\Requests\MediaUploadRequest;
use Modules\Core\Support\TenantStorage;
use Modules\Pump\Actions\ImportPumps\ImportPumpsAction;
use Modules\Pump\Actions\ImportPumpsPriceListsAction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\PumpableRequest;
use Modules\Pump\Services\Pumps\PumpsService;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class PumpsController extends Controller
{
    use UsesTenantModel;

    protected PumpsService $service;

    public function __construct(PumpsService $service)
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
        $this->authorize('pump_access');
        return $this->service->index();
    }


    /**
     * @param PumpableRequest $request
     * @return JsonResponse
     */
    public function load(PumpableRequest $request): JsonResponse
    {
        return $this->service->load();
    }

    /**
     * Display the specified resource.
     *
     * @param PumpableRequest $request
     * @param Pump $pump
     * @return Response
     * @throws AuthorizationException
     */
    public function show(PumpableRequest $request, Pump $pump): Response
    {
        $this->authorize('pump_show');
        return $this->service->show($pump);
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
        return (new ImportPumpsAction())->execute($request->file('files'));
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
