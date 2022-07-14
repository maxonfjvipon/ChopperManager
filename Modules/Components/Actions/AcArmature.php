<?php

namespace Modules\Components\Actions;

use App\Support\ArrForFiltering;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrValues;
use Modules\Components\Entities\Armature;
use Modules\Components\Entities\ArmatureType;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;

/**
 * Armature action.
 */
final class AcArmature extends AcComponents
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(
            new ArrForFiltering([
                'dns' => DN::values(),
                'connection_types' => ConnectionType::getDescriptions(),
                'armature_types' => ArmatureType::getDescriptions(),
            ]),
            'armature',
            new ArrValues(
                new ArrMapped(
                    Armature::allOrCached()->all(),
                    fn (Armature $armature) => [
                        'id' => $armature->id,
                        'article' => $armature->article,
                        'type' => $armature->type->description,
                        'connection_type' => $armature->connection_type->description,
                        'dn' => $armature->dn,
                        'length' => $armature->length,
                        'weight' => $armature->weight,
                        'price' => $armature->price,
                        'currency' => $armature->currency->description,
                        'price_updated_at' => formatted_date($armature->price_updated_at),
                    ]
                )
            )
        );
    }
}
