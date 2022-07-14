<?php

namespace Modules\Components\Entities;

use App\Models\Enums\Currency;
use App\Traits\Cached;
use App\Traits\HasPriceByRates;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\Pump;

/**
 * Collector.
 *
 * @property int               $dn_common
 * @property int               $dn_pipes
 * @property CollectorMaterial $material
 */
final class Collector extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Cached;
    use HasPriceByRates;

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'type' => CollectorType::class,
        'currency' => Currency::class,
        'connection_type' => ConnectionType::class,
        'material' => CollectorMaterial::class,
        'price_updated_at' => 'datetime:d.m.Y',
    ];

    protected static function getCacheKey(): string
    {
        return 'collectors';
    }

    /**
     * @throws Exception
     */
    public static function forSelection(
        string $stationType,
        Pump $pump,
        int $pumpsCount,
        array $dnMaterial,
        int $minDn,
    ): Collection|array {
        return self::allOrCached()
            ->where('dn_common', max($minDn, $dnMaterial['dn']))
            ->whereIn('dn_pipes', [$pump->dn_suction, $pump->dn_pressure])
            ->where('pipes_count', $pumpsCount)
            ->where('material.value', CollectorMaterial::getValueByDescription($dnMaterial['material']))
            ->where('connection_type.value', $pump->dn_suction <= 50
                ? ConnectionType::Threaded
                : ConnectionType::Flanged
            )
            ->where('type.value', CollectorType::getTypeByStationType($stationType));
    }
}
