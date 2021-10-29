<?php

namespace App\Models\Pumps;

use App\Models\Discount;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpBrand extends Model
{
    protected $fillable = ['name'];
    protected $softCascade = ['series'];
    public $timestamps = false;
    use HasFactory, SoftDeletes, SoftCascadeTrait, UsesTenantConnection;

    public function series(): HasMany
    {
        return $this->hasMany(PumpSeries::class, 'brand_id');
    }

    public function pumps(): HasManyThrough
    {
        return $this->hasManyThrough(Pump::class, PumpSeries::class, 'brand_id', 'series_id');
    }

    public function discounts(): MorphMany
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
}
