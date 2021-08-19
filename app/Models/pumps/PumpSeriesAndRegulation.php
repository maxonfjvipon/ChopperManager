<?php

namespace App\Models\Pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PumpSeriesAndRegulation extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory;

    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }

    public function regulation(): BelongsTo
    {
        return $this->belongsTo(PumpRegulation::class, 'relation_id');
    }
}
