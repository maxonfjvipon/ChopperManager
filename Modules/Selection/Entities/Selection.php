<?php

namespace Modules\Selection\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Modules\Core\Support\Rates;
use Modules\Selection\Traits\WithSelectionAttributes;
use Modules\Selection\Traits\WithSelectionCurves;
use Modules\Selection\Traits\WithSelectionRelationships;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Selection extends Model
{
    use HasFactory, SoftDeletes, UsesTenantConnection, WithSelectionRelationships, WithSelectionAttributes, WithSelectionCurves;

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
     * Calc pump prices (simple and discounted) and set them as attributes
     * @param Rates $rates
     * @return $this
     */
    public function withPrices(Rates $rates): self
    {
        $pump_prices = $this->pump->currentPrices($rates);
        $this->{'discounted_price'} = round($pump_prices['discounted'], 1) ?? null;
        $this->{'total_discounted_price'} = round($pump_prices['discounted'] * $this->total_pumps_count, 1) ?? null;
        $this->{'retail_price'} = round($pump_prices['simple'], 1) ?? null;
        $this->{'total_retail_price'} = round($pump_prices['simple'] * $this->total_pumps_count, 1) ?? null;
        return $this;
    }

    /**
     * @return Selection
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
}
