<?php

namespace App\Models;

namespace Modules\Pump\Entities;

use App\Models\Enums\Currency;
use App\Traits\Cached;
use App\Traits\HasPriceByRates;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use Modules\Pump\Traits\PumpAttributes;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Selection\Support\Performance\PumpPerformance;
use Modules\Selection\Support\Performance\SPPerformance;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * Pump.
 *
 * @property int $id
 * @property string $performance
 * @property int $dn_suction
 * @property int $dn_pressure
 * @property float $power
 * @property float $weight
 * @property string $article
 * @property int $ptp_length
 * @property string $name
 * @property int $series_id
 * @property int $suction_height
 * @property float $price
 * @property bool $is_discontinued_with_series
 * @property string $size
 * @property float $current
 *
 * @property PumpSeries $series
 * @property Carbon $price_updated_at
 * @property Collection<PumpCoefficients>|PumpCoefficients[] $coefficients
 * @property CollectorSwitch $collector_switch
 * @property Currency $currency
 * @property ConnectionType $connection_type
 * @property PumpOrientation $orientation
 *
 * @method static Pump find(int $id)
 */
final class Pump extends Model
{
    use HasFactory, SoftDeletes, BelongsToThrough;
    use PumpAttributes, Cached, HasPriceByRates;

    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'connection_type' => ConnectionType::class,
        'orientation' => PumpOrientation::class,
        'collector_switch' => CollectorSwitch::class,
        'price_updated_at' => 'datetime:d.m.Y'
    ];

    /**
     * @return string
     */
    protected static function getCacheKey(): string
    {
        return 'pumps';
    }

    /**
     * @return bool
     */
    #[Pure] public function isHorizontal(): bool
    {
        return $this->orientation->is(PumpOrientation::Horizontal);
    }

    /**
     * Creates performance for pump and caches it
     * @return PumpPerformance
     */
    public function performance(): PumpPerformance
    {
        if (!$this->getAttribute('__performance')) {
            $this->{'__performance'} = new SPPerformance($this);
        }
        return $this->__performance;
    }

    /**
     * Coefficient at {@code $position}
     *
     * @param int $position
     * @return PumpCoefficients
     * @throws Exception
     */
    public function coefficientsAt(int $position): PumpCoefficients
    {
        return $this->coefficients
                ->where('position', '=', $position)
                ->first()
            ?? PumpCoefficients::createdForPumpAtPosition($this, $position);
    }

    // RELATIONSHIPS

    /**
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function brand(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(PumpBrand::class, PumpSeries::class, null, '', [
            PumpBrand::class => 'brand_id',
            PumpSeries::class => 'series_id'
        ]);
    }

    /**
     * @return BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }

    /**
     * @return HasMany
     */
    public function coefficients(): HasMany
    {
        return $this->hasMany(PumpCoefficients::class, 'pump_id');
    }
}
