<?php

namespace Modules\Components\Entities;

use App\Interfaces\Rates;
use App\Models\Enums\Currency;
use App\Traits\Cached;
use App\Traits\HasPriceByRates;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Numerable\ArraySum;
use Modules\Components\Support\Armature\AF\ArmaAF;
use Modules\Components\Support\Armature\ArrArmatureCount;
use Modules\Components\Support\Armature\WS\ArmaWS;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

/**
 * Armature.
 *
 * @property int            $id
 * @property string         $article
 * @property int            $dn
 * @property int            $length
 * @property float          $weight
 * @property float          $price
 * @property Currency       $currency
 * @property Carbon         $price_updated_at
 * @property ConnectionType $connection_type
 * @property ArmatureType   $type
 */
final class Armature extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Cached;
    use HasPriceByRates;

    protected $guarded = [];

    protected $table = 'armature';

    public $timestamps = false;

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
     * @throws Exception
     */
    public static function weight(Pump $pump, int $stationType, int $pumpsCount): float|int|null
    {
        return self::sumBy('weight', $pump, $stationType, $pumpsCount);
    }

    /**
     * @throws Exception
     */
    public static function price(Pump $pump, int $stationType, int $pumpsCount, Rates $rates, ?Collector $inputCollector): float|int|null
    {
        return self::sumBy('price', $pump, $stationType, $pumpsCount, $rates, $inputCollector);
    }

    /**
     * @throws Exception
     */
    private static function sumBy(string $field, Pump $pump, int $stationType, int $pumpsCount, Rates $rates = null, Collector $inputCollector = null): float|int|null
    {
        $bad = false;
        $sum = ArraySum::new(
            new ArrMapped(
                self::armatureByStationType($pump, $stationType, $pumpsCount, $inputCollector),
                function (?ArrArmatureCount $armatureCount) use (&$bad, $field, $rates) {
                    if (($armature = ($acEntity = $armatureCount->asArray())['armature']) != null) {
                        return ((bool) $rates
                                ? $armature->priceByRates($rates)
                                : $armature->{$field})
                            * $acEntity['count'];
                    } else {
                        $bad = true;

                        return 0;
                    }
                }
            )
        )->asNumber();

        return $bad ? null : $sum;
    }

    /**
     * @param $pumpsCount
     *
     * @throws Exception
     */
    private static function armatureByStationType(Pump $pump, int $stationType, $pumpsCount, ?Collector $inputCollector): ArmaWS|ArmaAF
    {
        return match ($stationType) {
            StationType::WS => new ArmaWS($pump, $pumpsCount),
            StationType::AF => new ArmaAF($pump, $pumpsCount, $inputCollector)
        };
    }
}
