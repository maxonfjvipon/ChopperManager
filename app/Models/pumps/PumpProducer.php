<?php

namespace App\Models\Pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PumpProducer extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;

    public function series(): HasMany
    {
        return $this->hasMany(PumpSeries::class, 'producer_id');
    }

    public function pumps(): HasManyThrough
    {
        return $this->hasManyThrough(Pump::class, PumpSeries::class, 'producer_id', 'series_id');
    }
}
