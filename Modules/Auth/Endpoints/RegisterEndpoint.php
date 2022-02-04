<?php

namespace Modules\Auth\Endpoints;

use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use App\Support\Take;
use Exception;
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
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return TkInertia::new("Auth::Register", [
            'businesses' => Business::all(),
            'countries' => Country::all()
                ->map(fn($country) => new CountryResource($country))
        ])->act();
    }
}
