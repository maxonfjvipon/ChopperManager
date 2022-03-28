<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Pump brand.
 */
class PumpBrand extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $table = "pump_brands";
    public $timestamps = false;
    protected $guarded = [];
    protected array $softCascade = ['series'];

    public function series(): HasMany
    {
        return $this->hasMany(PumpSeries::class, 'brand_id');
    }
}
