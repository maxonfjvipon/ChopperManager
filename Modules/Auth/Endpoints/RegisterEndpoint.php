<?php

namespace Modules\Auth\Endpoints;

use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Transformers\CountryResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Register endpoint.
 * @package Modules\Auth\Takes
 */
final class RegisterEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return TkInertia::withStrComponent("Auth::Register")
            ->withArrayProps([
                'businesses' => Business::all(),
                'countries' => Country::all()
                    ->map(fn($country) => new CountryResource($country))
            ])->act();
    }
}
