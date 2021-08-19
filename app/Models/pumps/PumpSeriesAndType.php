<?php

namespace App\Models\pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PumpSeriesAndType extends Model
{
    protected $table = 'pump_series_and_types';
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PumpType::class, 'type_id');
    }
}
