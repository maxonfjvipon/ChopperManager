<?php

namespace Modules\Components\Actions;

use App\Support\ArrForFiltering;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrValues;
use Modules\Components\Entities\Armature;
use Modules\Components\Entities\ArmatureType;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\CollectorMaterial;
use Modules\Components\Entities\CollectorType;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;

final class AcCollectors extends AcComponents
{
    /**
     * Ctor.
     * @throws Exception
     */
    public function __construct()
    {
        $collectors = Collector::allOrCached();
        parent::__construct(
            new ArrForFiltering([
                'pipes_count' => [2, 3, 4, 5, 6],
                'dns' => DN::values(),
                'connection_types' => ConnectionType::getDescriptions(),
                'materials' => CollectorMaterial::getDescriptions(),
            ]),
            'collectors',
            new ArrValues(
                new ArrMapped(
                    CollectorType::getInstances(),
                    fn(CollectorType $collectorType) => [
                        'collector_type' => $collectorType->description . " коллектор",
                        'items' => array_values(
                            array_map(
                                fn(Collector $collector) => [
                                    'id' => $collector->id,
                                    'article' => $collector->article,
                                    'dn_common' => $collector->dn_common,
                                    'dn_pipes' => $collector->dn_pipess,
                                    'pipes_count' => $collector->pipes_count,
                                    'length' => $collector->length,
                                    'pipes_length' => $collector->pipes_length,
                                    'connection_type' => $collector->connection_type->description,
                                    'material' => $collector->material->description,
                                    'weight' => $collector->weight,
                                    'price' => $collector->price,
                                    'currency' => $collector->currency->description,
                                    'price_updated_at' => date_format($collector->price_updated_at, 'd.m.Y')
                                ],
                                $collectors->where('type.value', $collectorType->value)
                                    ->all()
                            )
                        )
                    ]
                )
            )
        );
    }
}
