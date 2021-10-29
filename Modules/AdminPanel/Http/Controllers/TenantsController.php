<?php

namespace Modules\AdminPanel\Http\Controllers;

use App\Traits\InModule;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class TenantsController extends Controller
{
    use InModule, UsesTenantModel;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('AdminPanel::Tenants/Index', [
            'tenants' => $this->getTenantModel()::with('type')->get()->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('adminpanel::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('adminpanel::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('adminpanel::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
