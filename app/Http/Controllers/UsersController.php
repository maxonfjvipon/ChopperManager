<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserTableResource;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        return Inertia::render('Users/Index', [
            'users' => User::allExceptLandlord()->map(fn($user) => [
                'id' => $user->id,
                'created_at' => $user->created_at,
                'organization_name' => $user->organization_name,
                'itn' => $user->itn,
                'phone' => $user->phone,
                'country' => $user->country->name,
                'city' => $user->city,
                'postcode' => $user->postcode,
                'currency' => $user->currency->code,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'main_business' => $user->business->name,
                'series' => "BL, TOP-S, TOP-Z",
                'selections' => 'Single pump, Pumping station water'
            ])->toArray()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
