<?php

namespace Modules\AdminPanel\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\Tenant;
use Modules\AdminPanel\Entities\TenantType;
use Modules\AdminPanel\Http\Requests\StoreTenantRequest;
use Modules\AdminPanel\Http\Requests\UpdateTenantRequest;
use Modules\AdminPanel\Transformers\TenantResource;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class TenantsController extends Controller
{
    use UsesTenantModel;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('AdminPanel::Tenants/Index', [
            'tenants' => $this->getTenantModel()::with(['type' => function ($query) {
                $query->select('id', 'name');
            }])->get()->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('AdminPanel::Tenants/Create', [
            'tenant_types' => TenantType::all(['id', 'name']),
            'selection_types' => SelectionType::all(['id', 'name']),
            'default_selection_types' => SelectionType::pluck('id')->all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreTenantRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTenantRequest $request): RedirectResponse
    {
        Tenant::createFromRequest($request);
        return Redirect::route('admin.tenants.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        return Inertia::render('AdminPanel::Tenants/Show', [
            'tenant' => new TenantResource($this->getTenantModel()::find($id)),
            'selection_types' => SelectionType::all(['id', 'name']),
            'tenant_types' => TenantType::get(['id', 'name'])->all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateTenantRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateTenantRequest $request, int $id): RedirectResponse
    {
        $this->getTenantModel()::find($id)->updateFromRequest($request);
        return Redirect::route('admin.tenants.index');
    }
}
