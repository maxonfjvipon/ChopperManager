<?php

namespace Modules\Auth\Endpoints;

use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
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
            'businesses' => Business::allOrCached(),
            'countries' => ArrMapped::new(
                [...Country::allOrCached()],
                fn(Country $country) => [
                    'id' => $country->id,
                    'name' => $country->country_code
                ]
            )->asArray()
        ])->act();
    }
}
