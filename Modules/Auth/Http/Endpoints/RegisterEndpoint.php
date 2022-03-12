<?php

namespace Modules\Auth\Http\Endpoints;

use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
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
        return (new TkInertia(
            "Auth::Register",
            new ArrMerged(
                ['businesses' => Business::allOrCached()->all()],
                new ArrObject(
                    "countries",
                    new ArrMapped(
                        Country::allOrCached()->all(),
                        fn(Country $country) => [
                            'id' => $country->id,
                            'name' => $country->country_code
                        ]
                    )
                )
            )
        ))->act();
    }
}
