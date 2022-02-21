<?php

namespace Modules\Selection\Entities;

use App\Support\Rates\Rates;
use App\Traits\WithOrWithoutTrashed;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Performance\PpHMax;
use Modules\Selection\Support\Performance\PumpPerfLines;
use Modules\Selection\Support\Point\IntersectionPoint;
use Modules\Selection\Support\Regression\EqFromPumpCoefficients;
use Modules\Selection\Support\SystemPerformance;
use Modules\Selection\Traits\AxisStep;
use Modules\Selection\Traits\SelectionAttributes;
use Modules\Selection\Traits\SelectionRelationships;

/**
 * Selection.
 * @property Pump $pump
 * @property array $curves_data
 * @property int $project_id
 * @property int $total_pumps_count
 * @property float $flow
 * @property float $head
 * @property int $reserve_pumps_count
 * @property int $pumps_count
 * @property string $pump_type
 * @property float $total_rated_power
 * @property int $id
 * @property mixed $created_at
 * @property string $selected_pump_name
 */
final class Selection extends Model
{
    use HasFactory, SoftDeletes, WithOrWithoutTrashed;
    use SelectionRelationships, SelectionAttributes, AxisStep;

    public $timestamps = false;
    protected $guarded = [];
    protected $table = "selections";

    protected $casts = [
        'created_at' => 'datetime:d.m.Y',
        'updated_at' => 'datetime:d.m.Y',
        'power_limit_checked' => 'boolean',
        'ptp_length_limit_checked' => 'boolean',
        'dn_suction_limit_checked' => 'boolean',
        'dn_pressure_limit_checked' => 'boolean',
        'use_additional_filters' => 'boolean',
    ];

    /**
     * @param Rates $rates
     * @return float|int
     * @throws Exception
     */
    public function totalRetailPrice(Rates $rates): float|int
    {
        return $this->pump->retailPrice($rates) * $this->total_pumps_count;

    }

    /**
     * Calc pump prices (simple and discounted) and set them as attributes
     * @param Rates $rates
     * @return $this
     * @throws Exception
     */
    public function withPrices(Rates $rates): self
    {
        $pump_prices = $this->pump->currentPrices($rates);
        $this->{'discounted_price'} = $pump_prices['discounted'];
        $this->{'total_discounted_price'} = $pump_prices['discounted'] * $this->total_pumps_count;
        $this->{'retail_price'} = $pump_prices['simple'];
        $this->{'total_retail_price'} = $pump_prices['simple'] * $this->total_pumps_count;
        return $this;
    }

    /**
     * Prepare selection for export.
     * @return Selection
     * @throws Exception
     */
    public function readyForExport(): Selection
    {
        return $this->load([
            'pump',
            'pump.connection_type',
            'pump.series',
            'pump.series.category',
            'pump.series.power_adjustment',
            'pump.brand',
            'dp_work_scheme',
        ])->withCurves();
    }

    /**
     * Calc selection prices and set them as {curves_data} attribute
     * @return $this
     * @throws Exception
     */
    public function withCurves(): self
    {
        $lines = (new PumpPerfLines($this->pump, $this->pumps_count ?? 2))->asArray();
        $yMax = (new PpHMax($this->pump->performance(), $this->head))->asNumber();
        $lastLine = $lines[count($lines) - 1];
        $xMax = max($lastLine[count($lastLine) - 1]['x'], $this->flow + 2 * $this->axisStep($this->flow));
        $data = [
            'performance_lines' => $lines,
            'dx' => 500 / $xMax,
            'dy' => 330 / $yMax,
            'x_axis_step' => $this->axisStep($xMax),
            'y_axis_step' => $this->axisStep($yMax),
        ];
        if ($this->flow !== null && $this->head !== null) {
            $intersectionPoint = new IntersectionPoint(
                EqFromPumpCoefficients::new(
                    $this->pump->coefficientsAt(
                        $this->dp_work_scheme_id ??
                        ($this->pumps_count - $this->reserve_pumps_count))
                ),
                $this->flow,
                $this->head
            );
            $data = (new ArrMerged(
                $data,
                new ArrObject(
                    "system_performance",
                    new SystemPerformance(
                        $this->flow,
                        $this->head,
                        max($intersectionPoint->y(), $this->head),
                        $intersectionPoint->x() < 50 ? 0.5 : 1
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
            ))->asArray();
        }
        $this->{'curves_data'} = $data;
        return $this;
    }

    /**
     * Update self and return
     * @param array $attributes
     * @return $this
     */
    public function updatedFrom(array $attributes = [])
    {
        $this->update($attributes);
        return $this;
    }
}
