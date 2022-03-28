<?php

namespace App\Models;

use App\Traits\HasConnectionType;
use App\Traits\HasCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * Pump.
 */
class Pump extends Model
{
    use HasFactory, HasCurrency, HasConnectionType, SoftDeletes, BelongsToThrough;

    protected $guarded = [];
    public $timestamps = false;

    // RELATIONSHIPS
    public function dn_suction(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_suction_id');
    }

    public function dn_pressure(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_pressure_id');
    }

    public function brand(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(PumpBrand::class, PumpSeries::class, null, '', [
            PumpBrand::class => 'brand_id',
            PumpSeries::class => 'series_id'
        ]);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }

    public function orientation(): BelongsTo
    {
        return $this->belongsTo(PumpOrientation::class, 'orientation_id');
    }

    public function collector_switch(): BelongsTo
    {
        return $this->belongsTo(CollectorSwitch::class, 'collector_switch_id');
    }
}
