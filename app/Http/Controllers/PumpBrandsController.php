<?php

namespace App\Http\Controllers;

use App\Http\Requests\PumpBrandUpdateRequest;
use App\Http\Requests\PumpBrandStoreRequest;
use App\Http\Resources\PumpBrandResource;
use App\Models\Pumps\PumpBrand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PumpBrandsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('PumpBrands/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PumpBrandStoreRequest $request
     * @return RedirectResponse
     */
    public function store(PumpBrandStoreRequest $request): RedirectResponse
    {
        PumpBrand::create($request->validated());
        return Redirect::route('pump_series.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PumpBrand $pumpBrand
     * @return Response
     */
    public function edit(PumpBrand $pumpBrand): Response
    {
        return Inertia::render('PumpBrands/Edit', [
            'brand' => new PumpBrandResource($pumpBrand)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PumpBrandUpdateRequest $request
     * @param PumpBrand $pumpBrand
     * @return RedirectResponse
     */
    public function update(PumpBrandUpdateRequest $request, PumpBrand $pumpBrand): RedirectResponse
    {
        $pumpBrand->update($request->validated());
        return Redirect::route('pump_series.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PumpBrand $pumpBrand
     * @return RedirectResponse
     */
    public function destroy(PumpBrand $pumpBrand): RedirectResponse
    {
        $pumpBrand->delete();
        return Redirect::route('pump_series.index');
    }

    /*
     * Restore the specified resource
     */
    public function restore($id): RedirectResponse
    {
        PumpBrand::withTrashed()->find($id)->restore();
        return Redirect::route('pump_series.index');
    }
}
