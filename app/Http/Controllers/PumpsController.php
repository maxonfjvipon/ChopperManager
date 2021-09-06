<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\PumpResource;
use App\Imports\PumpsDataImport;
use App\Imports\PumpsImport;
use App\Models\Pumps\Pump;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PumpsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        // TODO: make one simple request
        return Inertia::render('Pumps/Index', [
            'pumps' => Pump::with('regulation')
                ->with('series')
                ->with('producer')
                ->with('category')
                ->with('applications')
                ->with('types')
                ->with('dn_suction')
                ->with('dn_pressure')
                ->with('currency')
                ->with('mains_phase')
                ->with('connection_type')
                ->get()
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
     * @param \Illuminate\Http\Request $request
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
     */
    public function show(Pump $pump): Response
    {
        return Inertia::render('Pumps/Show', [
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
     * @param \Illuminate\Http\Request $request
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

    public function import(FileUploadRequest $request): RedirectResponse
    {
        $file = $request->validated()['file'];

        try {
            (new PumpsImport())->import($file);
            (new PumpsDataImport())->import($file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $exception) {
            throw ValidationException::withMessages(array_map(function ($failure) {
                return [__('validation.pumps_import.row') . ' ' . $failure->row() . ": " . $failure->errors()[0]];
            }, $exception->failures()));
        }

        return Redirect::route('pumps.index')->with('success', __('flash.pumps.imported'));
    }

}
