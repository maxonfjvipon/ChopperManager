<?php

namespace App\Models\Pumps;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PumpSeries extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory;

    public function brand(): BelongsTo
    {
        return $this->belongsTo(PumpBrand::class, 'brand_id', 'id');
    }

    public function pump(): HasMany
    {
        return $this->hasMany(Pump::class, 'series_id');
    }

    public function power_adjustment(): BelongsTo
    {
        return $this->belongsTo(ElPowerAdjustment::class, 'regulation_adjustment_id');
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(PumpType::class, 'pump_series_and_types', 'series_id', 'type_id');
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(PumpApplication::class, 'pump_series_and_applications', 'series_id', 'application_id');
    }

    public function discounts(): MorphMany
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
}
