<?php

namespace App\Models\Pumps;

use App\Models\ConnectionType;
use App\Models\Currency;
use App\Models\MainsConnection;
use App\Models\DN;
use App\Models\Selections\Single\SinglePumpSelection;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inertia\Testing\Concerns\Has;
use Znck\Eloquent\Traits\BelongsToThrough;

class Pump extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $softCascade = ['selections'];

    use SoftDeletes, HasFactory, BelongsToThrough, SoftCascadeTrait;

    public function getFullNameAttribute(): string
    {
        return "{$this->brand->name} {$this->series->name} {$this->name}";
    }

    public function getTypesAttribute(): string
    {
        return $this->series->imploded_types;
    }

    public function getApplicationsAttribute(): string
    {
        return $this->series->imploded_applications;
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }

    public function brand(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(PumpBrand::class, PumpSeries::class, null, '', [
            PumpBrand::class => 'brand_id',
            PumpSeries::class => 'series_id'
        ]);
    }

    public function selections(): HasMany
    {
        return $this->hasMany(SinglePumpSelection::class);
    }

//    public function applications(): BelongsToMany
//    {
//        return $this->belongsToMany(PumpApplication::class, 'pumps_and_applications', 'pump_article_num', 'application_id');
//    }
//
//    public function types(): BelongsToMany
//    {
//        return $this->belongsToMany(PumpType::class, 'pumps_and_types', 'pump_article_num', 'type_id');
//    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function connection_type(): BelongsTo
    {
        return $this->belongsTo(ConnectionType::class);
    }

    public function dn_suction(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_suction_id');
    }

    public function dn_pressure(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_pressure_id');
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(MainsConnection::class, 'connection_id');
    }

    public function coefficients(): HasMany
    {
        return $this->hasMany(PumpsAndCoefficients::class);
    }
}
