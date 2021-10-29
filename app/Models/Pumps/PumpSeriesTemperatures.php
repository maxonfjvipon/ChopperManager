<?php

namespace App\Models\Pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpSeriesTemperatures extends Model
{
    protected $table = 'pump_series_temperatures';
    use HasFactory, UsesTenantConnection;
    protected $guarded = [];
    public $timestamps = false;

    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }
}
