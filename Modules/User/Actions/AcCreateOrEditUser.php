<?php

namespace Modules\User\Actions;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\User\Transformers\RcUserToEdit;
use Modules\User\Entities\Area;
use Modules\User\Entities\User;
use Modules\User\Entities\UserRole;

/**
 * Create or edit user action.
 */
final class AcCreateOrEditUser extends ArrEnvelope
{
    /**
     * @throws Exception
     */
    public function __construct(private ?User $user = null)
    {
        parent::__construct(
            new ArrMerged(
                [
                    "filter_data" => [
                        'series' => array_map(
                            fn(PumpSeries $series) => [
                                'id' => $series->id,
                                'name' => $series->brand->name . " " . $series->name
                            ],
                            PumpSeries::with('brand')->get()->all(),
                        ),
                        'areas' => Area::allOrCached(),
                        'roles' => UserRole::asArrayForSelect()
                    ]
                ],
                new ArrIf(
                    !!$this->user,
                    fn() => ['user' => new RcUserToEdit($this->user)]
                )
            )
        );
    }
}
