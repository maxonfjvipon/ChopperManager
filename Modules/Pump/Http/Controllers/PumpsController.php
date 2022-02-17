<?php

namespace Modules\Pump\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\TenantStorage;
use Box\Spout\Common\Exception\IOException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Core\Http\Requests\FilesUploadRequest;
use Modules\Core\Http\Requests\MediaUploadRequest;
use Modules\Pump\Actions\ImportPumps\ImportPumpsAction;
use Modules\Pump\Actions\ImportPumpsPriceListsAction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\AddPumpToProjectsRequest;
use Modules\Pump\Http\Requests\LoadPumpsRequest;
use Modules\Pump\Http\Requests\PumpableRequest;
use Modules\Pump\Http\Requests\PumpShowRequest;
use Modules\Pump\Services\Pumps\PumpsService;
use Modules\Selection\Entities\Selection;
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
     * @param LoadPumpsRequest $request
     * @return JsonResponse
     */
    public function load(LoadPumpsRequest $request): JsonResponse
    {
        return $this->service->load($request);
    }

    /**
     * Display the specified resource.
     *
     * @param PumpShowRequest $request
     * @param Pump $pump
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(PumpShowRequest $request, Pump $pump): JsonResponse
    {
        $this->authorize('pump_show');
        return $this->service->show($request, $pump);
    }

    /**
     * Import
     *
     * @param FilesUploadRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws IOException
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

    /**
     * Add pump to projects
     * @param AddPumpToProjectsRequest $request
     * @param Pump $pump
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function addToProjects(AddPumpToProjectsRequest $request, Pump $pump): JsonResponse
    {
        $this->authorize('pump_show');
        foreach ($request->project_ids as $project_id) {
            $this->authorize('project_access_' . $project_id);
        }
        $selections = [];
        foreach ($request->project_ids as $project_id) {
            $selections[] = [
                'project_id' => $project_id,
                'selected_pump_name' => $request->pumps_count . ' ' . $pump->full_name,
                'pumps_count' => $request->pumps_count,
                'main_pumps_counts' => $request->pumps_count,
                'pump_id' => $pump->id,
            ];
        }
        DB::table(Tenant::current()->database . '.selections')->insert($selections);
        return response()->json(['success', 'Pump was added successfully']);
    }
}
