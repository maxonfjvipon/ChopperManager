<?php

namespace App\Models\pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PumpSeriesTemperatures extends Model
{
    protected $table = 'pump_series_temperatures';
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }
}
