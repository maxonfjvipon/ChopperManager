<?php

namespace Modules\Components\Actions;

use App\Support\ArrForFiltering;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrValues;
use Modules\Components\Entities\Chassis;

/**
 * Chassis action.
 */
final class AcChassis extends AcComponents
{
    /**
     * Ctor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(
            new ArrForFiltering(['pumps_count' => [2, 3, 4, 5, 6]]),
            "chassis",
            new ArrValues(
                new ArrMapped(
                    Chassis::allOrCached()->all(),
                    fn(Chassis $chassis) => [
                        'id' => $chassis->id,
                        'article' => $chassis->article,
                        'pumps_count' => $chassis->pumps_count,
                        'pumps_weight' => $chassis->pumps_weight,
                        'weight' => $chassis->weight,
                        'price' => $chassis->price,
                        'currency' => $chassis->currency->description,
                        'price_updated_at' => formatted_date($chassis->price_updated_at)
                    ],
                )
            )
        );
    }
}
