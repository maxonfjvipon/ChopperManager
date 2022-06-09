<?php

namespace Modules\Components\Actions;

use App\Support\ArrForFiltering;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrValues;
use Modules\Components\Entities\AssemblyJob;
use Modules\Components\Entities\CollectorType;
use Modules\Components\Entities\ControlSystemType;

final class AcAssemblyJobs extends AcComponents
{
    /**
     * Ctor.
     * @throws Exception
     */
    public function __construct()
    {
        $jobs = AssemblyJob::with('control_system_type')->get();
        parent::__construct(
            new ArrForFiltering([
                'pumps_counts' => [2, 3, 4, 5, 6],
            ]),
            'jobs',
            new ArrValues(
                new ArrMapped(
                    CollectorType::getInstances(),
                    fn(CollectorType $collectorType) => [
                        'collector_type' => $collectorType->description . " коллектор",
                        'items' => array_values(
                            array_map(
                                function (ControlSystemType $controlSystemType) use ($collectorType, $jobs) {
                                    return [
                                        'control_system_type' => $controlSystemType->name,
                                        'items' => array_values(
                                            array_map(
                                                fn(AssemblyJob $job) => [
                                                    'id' => $job->id,
                                                    'pumps_count' => $job->pumps_count,
                                                    'pumps_weight' => $job->pumps_weight,
                                                    'price' => $job->price,
                                                    'currency' => $job->currency->key,
                                                    'price_updated_at' => $job->price_updated_at,
                                                ],
                                                $jobs->where('collector_type.value', $collectorType->value)
                                                    ->where('control_system_type_id', $controlSystemType->id)
                                                    ->all()
                                            )
                                        )
                                    ];
                                },
                                $jobs->where('collector_type.value', $collectorType->value)
                                    ->map(fn(AssemblyJob $job) => $job->control_system_type)
                                    ->unique()
                                    ->all()
                            )
                        )
                    ]
                )
            )
        );
    }
}
