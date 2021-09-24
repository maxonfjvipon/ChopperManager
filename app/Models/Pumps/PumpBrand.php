<?php

namespace App\Models\Pumps;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PumpBrand extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;

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