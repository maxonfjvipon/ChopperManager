<?php

namespace Modules\Components\Entities;

use App\Models\Enums\Currency;
use App\Support\Rates\Rates;
use App\Traits\Cached;
use App\Traits\HasPriceByRates;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JetBrains\PhpStorm\Pure;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMappedKeyValue;
use Maxonfjvipon\Elegant_Elephant\Numerable\ArraySum;
use Modules\Components\Support\Armature\ArrArmatureCount;
use Modules\Components\Support\Armature\Fire\ArmaFire;
use Modules\Components\Support\Armature\Water\ArmaWater;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

/**
 * Armature.
 * @property-read int $id
 * @property-read string $article
 * @property-read int $dn
 * @property-read int $length
 * @property-read float $weight
 * @property-read float $price
 *
 * @property Currency $currency
 * @property Carbon $price_updated_at
 * @property ConnectionType $connection_type
 * @property ArmatureType $type
 */
final class Armature extends Model
{
    use HasFactory, SoftDeletes, Cached;
    use HasPriceByRates;

    protected $guarded = [];
    protected $table = "armature";
    public $timestamps = false;

    /**
     * @return string
     */
    protected static function getCacheKey(): string
    {
        return 'armature';
    }

    protected $casts = [
        'currency' => Currency::class,
        'type' => ArmatureType::class,
        'connection_type' => ConnectionType::class,
        'price_updated_at' => 'datetime:d.m.Y',
    ];

    /**
     * @param Pump $pump
     * @param int $stationType
     * @param int $pumpsCount
     * @return float|int|null
     * @throws Exception
     */
    public static function weight(Pump $pump, int $stationType, int $pumpsCount): float|int|null
    {
        return self::sumBy('weight', $pump, $stationType, $pumpsCount);
    }

    /**
     * @param Pump $pump
     * @param int $stationType
     * @param int $pumpsCount
     * @param Rates $rates
     * @param Collector|null $inputCollector
     * @return float|int|null
     * @throws Exception
     */
    public static function price(Pump $pump, int $stationType, int $pumpsCount, Rates $rates, ?Collector $inputCollector): float|int|null
    {
        return self::sumBy('price', $pump, $stationType, $pumpsCount, $rates, $inputCollector);
    }

    /**
     * @param string $field
     * @param Pump $pump
     * @param int $stationType
     * @param int $pumpsCount
     * @param Rates|null $rates
     * @param Collector|null $inputCollector
     * @return float|int|null
     * @throws Exception
     */
    private static function sumBy(string $field, Pump $pump, int $stationType, int $pumpsCount, Rates $rates = null, Collector $inputCollector = null): float|int|null
    {
        $bad = false;
        $sum = (new ArraySum(
            new ArrMapped(
                self::armatureByStationType($pump, $stationType, $pumpsCount, $inputCollector),
                function (ArrArmatureCount $armatureCount) use (&$bad, $field, $rates) {
                    $armatureCount = $armatureCount->asArray();
                    if (($armature = $armatureCount['armature']) != null) {
                        return (!!$rates
                                ? $armature->priceByRates($rates)
                                : $armature->{$field})
                            * $armatureCount['count'];
                    } else {
                        $bad = true;
                        return 0;
                    }
                }
            )
        ))->asNumber();
        return $bad ? null : $sum;
    }

    /**
     * @param Pump $pump
     * @param int $stationType
     * @param $pumpsCount
     * @param Collector|null $inputCollector
     * @return ArmaWater|ArmaFire
     */
    #[Pure] private static function armatureByStationType(Pump $pump, int $stationType, $pumpsCount, ?Collector $inputCollector): ArmaWater|ArmaFire
    {
        return match ($stationType) {
            StationType::WS => new ArmaWater($pump, $pumpsCount),
            StationType::AF => new ArmaFire($pump, $pumpsCount, $inputCollector)
        };
    }
}
