<?php

namespace Modules\PumpProducer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\PumpProducer\Entities\User;
use Modules\User\Transformers\UserResource;

class UsersController extends \Modules\User\Http\Controllers\UsersController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('PumpProducer::Users/Index', [
            'users' => User::with(['country' => function ($query) {
                $query->select('id', 'name');
            }, 'business'])->get(['id', 'created_at', 'email', 'organization_name',
                'city', 'country_id', 'first_name', 'middle_name', 'last_name', 'postcode', 'business_id'])->all()
        ]);
    }
}
