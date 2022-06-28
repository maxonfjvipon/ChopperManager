<?php

namespace Modules\Selection\Entities;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Performance\PpHMax;
use Modules\Selection\Support\Performance\PpXMax;
use Modules\Selection\Support\Performance\PumpPerfLines;
use Modules\Selection\Support\Point\IntersectionPoint;
use Modules\Selection\Support\Regression\EqFromPumpCoefficients;
use Modules\Selection\Support\SystemPerformance;
use Modules\Selection\Traits\AxisStep;
use Modules\Selection\Traits\PumpStationAttributes;
use Modules\Selection\Traits\PumpStationRelationships;

/**
 * Pump station.
 *
 * @property int $main_pumps_count
 * @property int $reserve_pumps_count
 * @property int $pumps_count
 * @property float $flow
 * @property float $head
 *
 * @property Selection $selection
 * @property Pump $pump
 */
final class PumpStation extends Model
{
    use HasFactory, SoftDeletes, AxisStep;
    use PumpStationRelationships, PumpStationAttributes;

    protected $guarded = [];

    protected $casts = [
        'station_type' => StationType::class
    ];

    /**
     * @return $this
     * @throws Exception
     */
    public function withCurves(): PumpStation
    {
        $this->{'curves'} = ArrMerged::new(
            [
                'performance_lines' => $lines = (new PumpPerfLines($this->pump, $this->pumps_count))->asArray(),
                'dx' => (($width = 550) - 100) / ($xMax = (new PpXMax($lines, $this->flow))->asNumber()),
                'dy' => (($height = 400) - 100) / ($yMax = (new PpHMax($this->pump->performance(), $this->head))->asNumber()),
                'x_axis_step' => $this->axisStep($xMax),
                'y_axis_step' => $this->axisStep($yMax),
                'width' => $width,
                'height' => $height
            ],
            new ArrIf(
                !!$this->flow && !!$this->head,
                function () {
                    $intersectionPoint = new IntersectionPoint(
                        new EqFromPumpCoefficients(
                            $this->pump->coefficientsAt($this->main_pumps_count)
                        ),
                        $this->flow,
                        $this->head
                    );
                    return new ArrMerged(
                        new ArrObject(
                            "system_performance",
                            new SystemPerformance(
                                $this->flow,
                                $this->head,
                                max($intersectionPoint->y(), $this->head),
                                $intersectionPoint->x() < 20 ? 0.1 : 1
                            )
                        ),
                        [
                            'working_point' => [
                                'flow' => $this->flow,
                                'head' => $this->head,
                            ],
                            'intersection_point' => [
                                'flow' => $intersectionPoint->x(),
                                'head' => $intersectionPoint->y(),
                            ]
                        ]
                    );
                }
            )
        )->asArray();
        return $this;
    }
}
