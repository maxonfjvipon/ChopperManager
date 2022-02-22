<?php

namespace Modules\Pump\Entities;

use App\Support\Rates\Rates;
use App\Traits\Cached;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pump\Traits\Pump\PumpAttributes;
use Modules\Pump\Traits\Pump\PumpRelationships;
use Modules\Pump\Traits\Pump\PumpScopes;
use Modules\Selection\Support\Performance\DPPerformance;
use Modules\Selection\Support\Performance\SPPerformance;
use Modules\Selection\Support\Performance\PumpPerformance;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * @property PumpsPriceList $price_list
 * @property string $pumpable_type
 * @property int $id
 * @property PumpSeries $series
 * @property PumpBrand $brand
 * @property string $name
 * @property string $sp_performance
 * @property string $dp_standby_performance
 * @property string $dp_peak_performance
 * @property mixed $coefficients
 * @property float $rated_power
 * @property string $article_num_main
 */
final class Pump extends Model
{
    public static string $SINGLE_PUMP = 'single_pump';
    public static string $DOUBLE_PUMP = 'double_pump';
    public static string $STATION_WATER = 'station_water';
    public static string $STATION_FIRE = 'station_fire';

    use HasFactory, SoftDeletes, SoftCascadeTrait, BelongsToThrough, Cached;
    use PumpRelationships, PumpScopes, PumpAttributes;

    protected array $softCascade = ['selections'];
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'pumps';

    protected $casts = [
        'is_discontinued' => 'boolean'
    ];

    protected static function getCacheKey(): string
    {
        return "pumps";
    }

    /**
     * TODO: mb make an attribute
     * Creates performance for pump and caches it
     * @return PumpPerformance
     */
    public function performance(): PumpPerformance
    {
        if (!$this->getAttribute('perf')) {
            $this->{'perf'} = match ($this->pumpable_type) {
                self::$SINGLE_PUMP => new SPPerformance($this),
                self::$DOUBLE_PUMP => new DPPerformance($this)
            };
        }
        return $this->perf;
    }

    /**
     * @throws Exception
     */
    public function coefficientsAt(int $position): PumpCoefficients
    {
        return $this->coefficients->firstWhere('position', $position)
            ?? PumpCoefficients::createdForPumpAtPosition($this, $position);
    }

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
     * @throws Exception
     */
    public function currentPrices(Rates $rates): array
    {
        if ($this->price_list) {
            $price = $rates->hasTheSameBaseAs($this->price_list->currency)
                ? $this->price_list->price
                : round($this->price_list->price / $rates->rateFor($this->price_list->currency->code));
            return [
                'simple' => $price,
                'discounted' => $price - $price * ($this->series->auth_discount->value
                        ?? $this->series->discount->value ?? 0) / 100
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
     * @throws Exception
     */
    public function retailPrice(Rates $rates): float|int
    {
        if ($this->price_list) {
            return $rates->hasTheSameBaseAs($this->price_list->currency)
                ? $this->price_list->price
                : round($this->price_list->price / $rates->rateFor($this->price_list->currency->code));
        }
        return 0;
    }
}
