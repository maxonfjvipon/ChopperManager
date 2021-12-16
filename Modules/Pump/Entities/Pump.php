<?php

namespace Modules\Pump\Entities;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Znck\Eloquent\Traits\BelongsToThrough;

class Pump extends Model
{
    public static string $SINGLE_PUMP = 'single_pump';
    public static string $DOUBLE_PUMP = 'double_pump';
    public static string $STATION_WATER = 'station_water';
    public static string $STATION_FIRE = 'station_fire';

    use SoftDeletes, SoftCascadeTrait, UsesTenantConnection, BelongsToThrough;

    protected $softCascade = ['selections'];
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'pumps';

    public function getFullNameAttribute(): string
    {
        return "{$this->brand->name} {$this->name}";
    }

    public function getTypesAttribute(): string
    {
        return $this->series->imploded_types;
    }

    public function getApplicationsAttribute(): string
    {
        return $this->series->imploded_applications;
    }

    public function scopeOnPumpableType($query, $pumpable_type)
    {
        return $query->where('pumpable_type', $pumpable_type);
    }

    public function scopeSinglePumps($query)
    {
        return $this->scopeOnPumpableType($query, self::$SINGLE_PUMP);
    }

    public function scopeAvailableForCurrentUser($query)
    {
        return $query->whereIn('id', Auth::user()->available_pumps()->pluck($this->getTable() . '.id')->all());
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

    public function price_list(): HasOne
    {
        return $this->hasOne(PumpsPriceList::class, 'pump_id')
            ->where('country_id', Auth::user()->country_id);
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

    public function mains_connection(): BelongsTo
    {
        return $this->belongsTo(MainsConnection::class, 'connection_id');
    }

    public function coefficients(): HasMany
    {
        return $this->hasMany(PumpsAndCoefficients::class, 'pump_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(PumpFile::class, 'pump_id');
    }
}
