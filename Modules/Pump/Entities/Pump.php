<?php

namespace Modules\Pump\Entities;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Support\Rates;
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

    protected $casts = [
        'is_discontinued' => 'boolean'
    ];

    // ATTRIBUTES
    public function getIsDiscontinuedWithSeriesAttribute(): bool
    {
        return $this->series->is_discontinued
            ? true
            : $this->getOriginal('is_discontinued');
    }

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

    // SCOPES
    public function scopePerformanceData($query, $seriesId)
    {
        return match (PumpSeries::find($seriesId)->category_id) {
            PumpCategory::$SINGLE_PUMP => $query->addSelect('sp_performance'),
            PumpCategory::$DOUBLE_PUMP => $query->addSelect('dp_peak_performance', 'dp_standby_performance'),
        };
    }

    public function scopeOnPumpableType($query, $pumpable_type)
    {
        return $query->where('pumpable_type', $pumpable_type);
    }

    public function scopeDoublePumps($query)
    {
        return $this->scopeOnPumpableType($query, self::$DOUBLE_PUMP);
    }

    public function scopeSinglePumps($query)
    {
        return $this->scopeOnPumpableType($query, self::$SINGLE_PUMP);
    }

    public function scopeAvailableForCurrentUser($query)
    {
        return $query->whereIn('id', Auth::user()->available_pumps()->pluck('pumps.id')->all());
    }

    // FUNCTIONS
    public function coefficientsCount(): int
    {
        return match ($this->pumpable_type) {
            self::$SINGLE_PUMP => 9,
            self::$DOUBLE_PUMP => 2,
        };
    }

    /**
     * @param Rates $rates
     * @return array
     */
    public function currentPrices(Rates $rates): array
    {
        if ($this->price_list) {
            $price = $this->price_list->currency->code === $rates->base()
                ? $this->price_list->price
                : round($this->price_list->price / $rates->rate($this->price_list->currency->code));
            return [
                'simple' => $price,
                'discounted' => $price - $price * ($this->series->discount->value ?? 0) / 100
            ];
        }
        return [
            'simple' => 0,
            'discounted' => 0
        ];
    }

    /**
     * @param Rates $rates
     * @return float|int
     */
    public function retailPrice(Rates $rates): float|int
    {
        if ($this->price_list) {
            return $this->price_list->currency->code === $rates->base()
                ? $this->price_list->price
                : round($this->price_list->price / $rates->rate($this->price_list->currency->code));
        }
        return 0;
    }

    // RELATIONSHIPS
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
