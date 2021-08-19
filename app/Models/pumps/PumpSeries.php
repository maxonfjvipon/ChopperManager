<?php

namespace App\Models\pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PumpSeries extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory;

    public function producer(): BelongsTo
    {
        return $this->belongsTo(PumpProducer::class, 'producer_id', 'id');
    }

    public function pump(): HasMany
    {
        return $this->hasMany(Pump::class, 'series_id');
    }

    public function temperatures(): HasOne
    {
        return $this->hasOne(PumpSeriesTemperatures::class, 'series_id');
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(PumpType::class, 'pump_series_and_types', 'series_id', 'type_id');
    }

    public function regulations(): BelongsToMany
    {
        return $this->belongsToMany(PumpRegulation::class, 'pump_series_and_regulations', 'series_id', 'regulation_id');
    }
}
