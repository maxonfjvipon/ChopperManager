<?php

namespace Modules\Pump\Entities;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pump\Traits\HasDiscount;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpBrand extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait, UsesTenantConnection, HasDiscount;

    protected $fillable = ['name'];
    protected $softCascade = ['series'];
    public $timestamps = false;

    public function series(): HasMany
    {
        return $this->hasMany(PumpSeries::class, 'brand_id');
    }

    public function pumps(): HasManyThrough
    {
        return $this->hasManyThrough(Pump::class, PumpSeries::class, 'brand_id', 'series_id');
    }
}
