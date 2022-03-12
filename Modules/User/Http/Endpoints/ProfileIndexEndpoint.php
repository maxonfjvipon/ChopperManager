<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Project\Entities\Currency;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Support\UserProfile;
use Symfony\Component\HttpFoundation\Response;

/**
 * Profile index controller.
 */
final class ProfileIndexEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkInertia(
            "User::Profile",
            new ArrMerged(
                new ArrObject('user', new UserProfile(Auth::user())),
                new ArrObject(
                    "currencies",
                    new ArrMapped(
                        Currency::allOrCached()->all(),
                        fn(Currency $currency) => [
                            'id' => $currency->id,
                            'name' => $currency->code_name,
                        ]
                    )
                ),
                [
                    'businesses' => Business::allOrCached()->all(),
                    'countries' => Country::allOrCached()->all(),
                    'discounts' => Auth::user()->formatted_discounts
                ]
            )
        ))->act();
    }
}
