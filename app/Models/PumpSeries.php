<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Pump series.
 */
class PumpSeries extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $table = "pump_series";
    public $timestamps = false;
    protected $guarded = [];
    protected array $softCascade = ['pumps'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(PumpBrand::class, 'brand_id', 'id');
    }

    public function pumps(): HasMany
    {
        return $this->hasMany(Pump::class, 'series_id');
    }
}
