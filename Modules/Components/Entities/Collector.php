<?php

namespace Modules\Components\Entities;

use App\Models\Enums\Currency;
use App\Traits\Cached;
use App\Traits\HasPriceByRates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;

/**
 * Collector.
 * @property int $dn_common
 *
 * @property CollectorMaterial $material
 */
final class Collector extends Model
{
    use HasFactory, SoftDeletes, Cached;
    use HasPriceByRates;

    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'type' => CollectorType::class,
        'currency' => Currency::class,
        'connection_type' => ConnectionType::class,
        'material' => CollectorMaterial::class,
        'price_updated_at' => 'datetime:d.m.Y'
    ];

    /**
     * @return string
     */
    protected static function getCacheKey(): string
    {
        return "collectors";
    }
}
