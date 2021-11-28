<?php

namespace Modules\Pump\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ModuleResourceController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Http\Requests\PumpBrandStoreRequest;
use Modules\Pump\Http\Requests\PumpBrandUpdateRequest;
use Modules\Pump\Transformers\PumpBrandResource;

class PumpBrandsController extends ModuleResourceController
{
    public function __construct()
    {
        parent::__construct(
            null,
            'Pump::PumpBrands/Create',
            null,
            'Pump::PumpBrands/Edit'
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create(): Response
    {
        $this->authorize('brand_create');
        return Inertia::render($this->createPath);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PumpBrandStoreRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(PumpBrandStoreRequest $request): RedirectResponse
    {
        $this->authorize('brand_create');
        PumpBrand::create($request->validated());
        return Redirect::route('pump_series.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PumpBrand $pumpBrand
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(PumpBrand $pumpBrand): Response
    {
        $this->authorize('brand_edit');
        return Inertia::render($this->editPath, [
            'brand' => new PumpBrandResource($pumpBrand)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PumpBrandUpdateRequest $request
     * @param PumpBrand $pumpBrand
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(PumpBrandUpdateRequest $request, PumpBrand $pumpBrand): RedirectResponse
    {
        $this->authorize('brand_edit');
        $pumpBrand->update($request->validated());
        return Redirect::route('pump_series.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PumpBrand $pumpBrand
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(PumpBrand $pumpBrand): RedirectResponse
    {
        $this->authorize('brand_delete');
        $pumpBrand->delete();
        return Redirect::route('pump_series.index');
    }

    /*
     * Restore the specified resource
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('brand_restore');
        PumpBrand::withTrashed()->find($id)->restore();
        return Redirect::route('pump_series.index');
    }
}
