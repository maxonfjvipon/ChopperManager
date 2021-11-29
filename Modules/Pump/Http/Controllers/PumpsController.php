<?php

namespace Modules\Pump\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ModuleResourceController;
use Illuminate\Support\Facades\Redirect;
use Modules\Core\Http\Requests\FilesUploadRequest;
use Modules\Core\Http\Requests\MediaUploadRequest;
use Modules\Core\Support\TenantStorage;
use Modules\Pump\Actions\ImportPumpsAction;
use Modules\Pump\Actions\ImportPumpsPriceListsAction;
use Modules\Pump\Entities\Pump;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Modules\Pump\Services\Pumps\PumpsService;
use Modules\Pump\Services\Pumps\PumpsServiceInterface;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class PumpsController extends Controller
{
    use UsesTenantModel;

    protected PumpsServiceInterface $service;

    public function __construct(PumpsServiceInterface $service)
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
        return $this->service->__index();
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
        return $this->service->__show($pump);
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
